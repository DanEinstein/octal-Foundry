"""
Groq AI Service for generating learning roadmaps
Uses Groq's fast inference API with Llama 3 model
Now includes AI-recommended YouTube videos (no YouTube API needed)
"""

import json
import logging
import requests
from typing import List, Dict

import sys
sys.path.append('..')
from config import settings

logger = logging.getLogger(__name__)


class GroqService:
    """Service for interacting with Groq Inference API"""
    
    API_URL = "https://api.groq.com/openai/v1/chat/completions"
    MODEL = "llama-3.3-70b-versatile"  # Fast and capable model
    
    def __init__(self):
        """Initialize with API key"""
        self.api_key = settings.GROQ_API_KEY
        if not self.api_key:
            logger.warning("Groq API key not found")
            
    def generate_roadmap(self, unit_code: str, unit_name: str, career_path: str = None, concurrent_units: List[str] = None) -> List[Dict]:
        """
        Generate a 12-week learning roadmap using Groq AI
        Now includes YouTube video recommendations for each week
        """
        
        context = ""
        if career_path:
            context += f"\nThe student is aiming for a career as a {career_path}. Ensure the roadmap emphasizes practical applications relevant to this career."
            
        if concurrent_units and len(concurrent_units) > 0:
            units_list = ", ".join(concurrent_units)
            context += f"\nThe student is concurrently studying: {units_list}. Look for synergies where this unit can reinforce concepts from these other courses."

        system_prompt = """You are an expert curriculum designer for university courses in Kenya. 
You create comprehensive 12-week learning roadmaps with curated YouTube video recommendations.
Always respond with valid JSON only, no additional text."""

        user_prompt = f"""Create a comprehensive 12-week learning roadmap for the following course:
- Course Code: {unit_code}
- Course Name: {unit_name}
{context}

For each week, provide:
1. A concise title (5-8 words)
2. A brief description (1-2 sentences)
3. 3-5 key topics to be covered
4. A "project_task" (Foundry Task): A specific, practical task or mini-project
5. 2-3 recommended YouTube videos with:
   - video_id: A real YouTube video ID that teaches this topic (11 characters)
   - title: The video title
   - channel: The channel name
   - duration: Estimated duration (e.g., "15:30")

Format your response as a JSON array with exactly 12 weeks. Each week object must use this exact structure:
{{
  "week": 1,
  "title": "Week title here",
  "description": "Week description here",
  "topics": ["Topic 1", "Topic 2", "Topic 3"],
  "project_task": "Build a specific component...",
  "videos": [
    {{
      "video_id": "dQw4w9WgXcQ",
      "title": "Video Title",
      "channel": "Channel Name",
      "duration": "10:30"
    }}
  ]
}}

IMPORTANT: 
- Use REAL YouTube video IDs from popular educational channels like freeCodeCamp, Traversy Media, CS50, MIT OpenCourseWare, CrashCourse, 3Blue1Brown, etc.
- If you don't know a real video ID, use a placeholder like "placeholder123" but try to use real ones.
- Ensure the roadmap progresses from fundamentals to advanced concepts.
- Return ONLY the JSON array, no additional text or markdown."""
        
        headers = {
            "Authorization": f"Bearer {self.api_key}",
            "Content-Type": "application/json"
        }
        
        payload = {
            "model": self.MODEL,
            "messages": [
                {"role": "system", "content": system_prompt},
                {"role": "user", "content": user_prompt}
            ],
            "temperature": 0.7,
            "max_tokens": 8192  # Increased for video data
        }

        try:
            logger.info(f"Generating roadmap for: {unit_code} - {unit_name} via Groq")
            
            response = requests.post(self.API_URL, headers=headers, json=payload, timeout=90)
            
            if response.status_code != 200:
                raise Exception(f"API Error {response.status_code}: {response.text}")
            
            response_json = response.json()
            generated_text = response_json['choices'][0]['message']['content']
            
            # Clean up text to extract JSON
            json_str = generated_text.strip()
            
            # Remove markdown code blocks if present
            if json_str.startswith("```json"):
                json_str = json_str[7:]
            if json_str.startswith("```"):
                json_str = json_str[3:]
            if json_str.endswith("```"):
                json_str = json_str[:-3]
            
            # Find JSON array
            start_idx = json_str.find('[')
            end_idx = json_str.rfind(']')
            
            if start_idx != -1 and end_idx != -1:
                json_str = json_str[start_idx:end_idx+1]
            
            roadmap = json.loads(json_str.strip())
            
            # Validate and fix roadmap
            if len(roadmap) != 12:
                logger.warning(f"Expected 12 weeks, got {len(roadmap)}")
                while len(roadmap) < 12:
                    roadmap.append({
                        "week": len(roadmap) + 1,
                        "title": f"Week {len(roadmap) + 1}: Advanced Topics",
                        "description": "Continuation of course material",
                        "topics": ["Review", "Practice", "Assessment"],
                        "project_task": "Review and consolidate learning from previous weeks.",
                        "videos": []
                    })
            
            # Normalize each week
            for i, week in enumerate(roadmap):
                week["week"] = i + 1
                if 'project_task' not in week:
                    week['project_task'] = "Review and practice weekly concepts."
                if 'videos' not in week:
                    week['videos'] = []
                
                # Normalize video data with thumbnail URLs
                for video in week.get('videos', []):
                    if 'video_id' in video:
                        video['thumbnail'] = f"https://img.youtube.com/vi/{video['video_id']}/mqdefault.jpg"
                    if 'channel' not in video:
                        video['channel'] = "Educational Channel"
                    if 'duration' not in video:
                        video['duration'] = "10:00"
            
            logger.info(f"Successfully generated roadmap with {len(roadmap)} weeks")
            return roadmap[:12]
            
        except json.JSONDecodeError as e:
            logger.error(f"Failed to parse Groq response as JSON: {str(e)}")
            raise Exception("AI response format error. Please try again.")
        except Exception as e:
            logger.error(f"Groq API error: {str(e)}")
            raise Exception(f"AI service error: {str(e)}")
