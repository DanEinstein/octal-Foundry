"""
Groq AI Service for generating learning roadmaps
Uses Groq's fast inference API with Llama 3 model
Integrated with LangChain Video Agent for career-focused video recommendations
"""

import json
import logging
import requests
from typing import List, Dict, Optional

import sys
sys.path.append('..')
from config import settings
from services.video_agent import get_video_agent

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
            
    def generate_roadmap(self, unit_code: str, unit_name: str, career_path: Optional[str] = None, concurrent_units: Optional[List[str]] = None) -> List[Dict]:
        """
        Generate a 12-week learning roadmap using Groq AI.
        
        Uses LangChain Video Agent to generate career-focused YouTube video
        recommendations that match the student's career path and learning context.
        """
        
        context = ""
        if career_path:
            context += f"\nThe student is aiming for a career as a {career_path}. Ensure the roadmap emphasizes practical applications relevant to this career."
            
        if concurrent_units and len(concurrent_units) > 0:
            units_list = ", ".join(concurrent_units)
            context += f"\nThe student is concurrently studying: {units_list}. Look for synergies where this unit can reinforce concepts from these other courses."

        # Simplified prompt - Video Agent handles video recommendations
        system_prompt = """You are an expert curriculum designer for university courses in Kenya. 
You create comprehensive 12-week learning roadmaps focused on practical skills.
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

Format your response as a JSON array with exactly 12 weeks. Each week object must use this exact structure:
{{
  "week": 1,
  "title": "Week title here",
  "description": "Week description here",
  "topics": ["Topic 1", "Topic 2", "Topic 3"],
  "project_task": "Build a specific component..."
}}

IMPORTANT: 
- Ensure the roadmap progresses from fundamentals to advanced concepts.
- Focus on practical, hands-on learning appropriate for the career path.
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
            
            # Normalize each week and add career-focused videos via Video Agent
            video_agent = get_video_agent()
            
            for i, week in enumerate(roadmap):
                week["week"] = i + 1
                if 'project_task' not in week:
                    week['project_task'] = "Review and practice weekly concepts."
                
                # Use LangChain Video Agent to get career-focused video recommendations
                week_topic = week.get('title', f"Week {i+1} Topics")
                if career_path:
                    try:
                        logger.info(f"Getting contextual videos for Week {i+1}: {week_topic}")
                        videos = video_agent.get_contextual_videos(
                            unit_name=unit_name,
                            career_path=career_path,
                            week_topic=week_topic
                        )
                        week['videos'] = videos
                    except Exception as e:
                        logger.warning(f"Video agent failed for week {i+1}: {str(e)}")
                        week['videos'] = []
                else:
                    # No career path specified - generate generic videos
                    try:
                        videos = video_agent.get_contextual_videos(
                            unit_name=unit_name,
                            career_path="General Education",
                            week_topic=week_topic
                        )
                        week['videos'] = videos
                    except Exception as e:
                        logger.warning(f"Video agent failed for week {i+1}: {str(e)}")
                        week['videos'] = []
            
            logger.info(f"Successfully generated roadmap with {len(roadmap)} weeks and career-focused videos")
            return roadmap[:12]
            
        except json.JSONDecodeError as e:
            logger.error(f"Failed to parse Groq response as JSON: {str(e)}")
            raise Exception("AI response format error. Please try again.")
        except Exception as e:
            logger.error(f"Groq API error: {str(e)}")
            raise Exception(f"AI service error: {str(e)}")

    def analyze_curriculum(self, course_name: str, year_of_study: int, 
                          current_semester: int, units: List[Dict], 
                          interests: List[str]) -> Dict:
        """
        Analyze student's curriculum and recommend practical courses
        based on their course, units, and interests
        """
        
        # Format units for prompt
        units_text = "\n".join([
            f"- {u.get('unit_code', 'N/A')}: {u['unit_name']}" 
            for u in units
        ])
        
        interests_text = ", ".join(interests) if interests else "Not specified"
        
        system_prompt = """You are an expert educational advisor for Kenyan university students.
Your role is to analyze a student's academic curriculum and recommend practical skills courses 
that will complement their studies and prepare them for the job market.

Always respond with valid JSON only, no additional text."""

        user_prompt = f"""Analyze this student's profile and recommend practical courses:

**Student Profile:**
- Course: {course_name}
- Year of Study: Year {year_of_study}, Semester {current_semester}
- Interests: {interests_text}

**Current Units:**
{units_text}

Based on this information, recommend 3-5 practical skills courses that will:
1. Complement their academic studies
2. Align with their stated interests
3. Prepare them for the job market in Kenya and globally
4. Build skills progressively appropriate for their year level

For each recommendation, provide:
- course_name: Name of the practical course
- description: 2-3 sentences explaining what they will learn
- skill_category: Category (e.g., "Data Science", "Web Development", "AI/ML", "Mobile Development")
- relevance_score: 1-100 based on how well it fits their profile
- why_recommended: 1-2 sentences explaining why this is perfect for them

Also provide:
- student_profile_summary: A brief summary of the student's academic focus
- primary_recommendation: The single best course for them to start with

Return JSON in this exact format:
{{
  "student_profile_summary": "Summary here",
  "recommended_courses": [
    {{
      "course_name": "...",
      "description": "...",
      "skill_category": "...",
      "relevance_score": 95,
      "why_recommended": "..."
    }}
  ],
  "primary_recommendation": {{
    "course_name": "...",
    "description": "...",
    "skill_category": "...",
    "relevance_score": 98,
    "why_recommended": "..."
  }}
}}"""
        
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
            "max_tokens": 4096
        }

        try:
            logger.info(f"Analyzing curriculum for: {course_name}, Year {year_of_study}")
            
            response = requests.post(self.API_URL, headers=headers, json=payload, timeout=60)
            
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
            
            # Find JSON object
            start_idx = json_str.find('{')
            end_idx = json_str.rfind('}')
            
            if start_idx != -1 and end_idx != -1:
                json_str = json_str[start_idx:end_idx+1]
            
            result = json.loads(json_str.strip())
            
            logger.info(f"Successfully analyzed curriculum, found {len(result.get('recommended_courses', []))} recommendations")
            return result
            
        except json.JSONDecodeError as e:
            logger.error(f"Failed to parse Groq response as JSON: {str(e)}")
            raise Exception("AI response format error. Please try again.")
        except Exception as e:
            logger.error(f"Groq API error: {str(e)}")
            raise Exception(f"AI service error: {str(e)}")

