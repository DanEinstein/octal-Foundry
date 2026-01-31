"""
Gemini AI Service for generating learning roadmaps
Uses Google's Gemini 1.5 Flash model
"""

import json
import logging
import google.generativeai as genai
from typing import List, Dict

import sys
sys.path.append('..')
from config import settings

logger = logging.getLogger(__name__)


class GeminiService:
    """Service for interacting with Google Gemini AI"""
    
    def __init__(self):
        """Initialize Gemini client with API key"""
        genai.configure(api_key=settings.GEMINI_API_KEY)
        self.model = genai.GenerativeModel('gemini-1.5-flash')
    
    
    def generate_roadmap(self, unit_code: str, unit_name: str, career_path: str = None, concurrent_units: List[str] = None) -> List[Dict]:
        """
        Generate a 12-week learning roadmap using Gemini AI
        
        Args:
            unit_code: Course code (e.g., "CIT 301")
            unit_name: Course name (e.g., "Machine Learning Fundamentals")
            career_path: Optional career goal (e.g., "Data Scientist")
            concurrent_units: Optional list of other units taken this semester
        
        Returns:
            List of 12 week dictionaries with title, description, topics, and project_task
        
        Raises:
            Exception: If API call fails or response parsing fails
        """
        
        context = ""
        if career_path:
            context += f"\nThe student is aiming for a career as a {career_path}. Ensure the roadmap emphasizes practical applications relevant to this career."
            
        if concurrent_units and len(concurrent_units) > 0:
            units_list = ", ".join(concurrent_units)
            context += f"\nThe student is concurrently studying: {units_list}. Look for synergies where this unit can reinforce concepts from these other courses."

        prompt = f"""
You are an expert curriculum designer for university courses in Kenya.

Create a comprehensive 12-week learning roadmap for the following course:
- Course Code: {unit_code}
- Course Name: {unit_name}
{context}

For each week, provide:
1. A concise title (5-8 words)
2. A brief description (1-2 sentences)
3. 3-5 key topics to be covered
4. A "project_task" (Foundry Task): A specific, practical task or mini-project that applies the week's concepts. This should be something the student can build, write, or solve.

Format your response as a JSON array with exactly 12 weeks. Each week should have:
{{
  "week": 1,
  "title": "Week title here",
  "description": "Week description here",
  "topics": ["Topic 1", "Topic 2", "Topic 3"],
  "project_task": "Build a specific component or solve a specific problem..."
}}

Ensure the roadmap:
- Progresses from fundamentals to advanced concepts
- Includes practical, hands-on topics
- Is suitable for university-level learning in Kenya
- Covers both theory and practical application
- Uses appropriate technical terminology

Return ONLY the JSON array, no additional text or markdown.
"""
        
        try:
            logger.info(f"Generating roadmap for: {unit_code} - {unit_name} (Career: {career_path})")
            
            response = self.model.generate_content(prompt)
            roadmap_text = response.text.strip()
            
            # Remove markdown code blocks if present
            if roadmap_text.startswith("```json"):
                roadmap_text = roadmap_text[7:]
            if roadmap_text.startswith("```"):
                roadmap_text = roadmap_text[3:]
            if roadmap_text.endswith("```"):
                roadmap_text = roadmap_text[:-3]
            
            roadmap = json.loads(roadmap_text.strip())
            
            # Validate we have 12 weeks
            if len(roadmap) != 12:
                logger.warning(f"Expected 12 weeks, got {len(roadmap)}")
                # Pad or trim to 12 weeks
                while len(roadmap) < 12:
                    roadmap.append({
                        "week": len(roadmap) + 1,
                        "title": f"Week {len(roadmap) + 1}: Advanced Topics",
                        "description": "Continuation of course material",
                        "topics": ["Review", "Practice", "Assessment"],
                        "project_task": "Review and consolidate learning from previous weeks."
                    })
                roadmap = roadmap[:12]
            
            # Ensure week numbers are correct
            for i, week in enumerate(roadmap):
                week["week"] = i + 1
            
            logger.info(f"Successfully generated roadmap with {len(roadmap)} weeks")
            return roadmap
            
        except json.JSONDecodeError as e:
            logger.error(f"Failed to parse Gemini response as JSON: {str(e)}")
            raise Exception(f"Failed to parse AI response: {str(e)}")
        except Exception as e:
            logger.error(f"Gemini API error: {str(e)}")
            raise Exception(f"AI service error: {str(e)}")
