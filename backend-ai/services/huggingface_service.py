"""
Hugging Face AI Service for generating learning roadmaps
Uses Hugging Face Inference API with Mistral-7B model
"""

import json
import logging
import requests
from typing import List, Dict, Optional
import time

import sys
sys.path.append('..')
from config import settings

logger = logging.getLogger(__name__)

class HuggingFaceService:
    """Service for interacting with Hugging Face Inference API"""
    
    # Using Mistral-7B-Instruct-v0.2 which is good for instruction following and free on HF Inference API
    # Updated URL as api-inference is deprecated
    API_URL = "https://router.huggingface.co/hf-inference/models/mistralai/Mistral-7B-Instruct-v0.2"
    
    def __init__(self):
        """Initialize with API key"""
        self.api_key = settings.HUGGINGFACE_API_KEY
        if not self.api_key:
            logger.warning("Hugging Face API key not found")
            
    def generate_roadmap(self, unit_code: str, unit_name: str, career_path: str = None, concurrent_units: List[str] = None) -> List[Dict]:
        """
        Generate a 12-week learning roadmap using Hugging Face AI
        """
        
        context = ""
        if career_path:
            context += f"\nThe student is aiming for a career as a {career_path}. Ensure the roadmap emphasizes practical applications relevant to this career."
            
        if concurrent_units and len(concurrent_units) > 0:
            units_list = ", ".join(concurrent_units)
            context += f"\nThe student is concurrently studying: {units_list}. Look for synergies where this unit can reinforce concepts from these other courses."

        # Mistral format [INST] instruction [/INST]
        prompt = f"""[INST] You are an expert curriculum designer for university courses in Kenya.

Create a comprehensive 12-week learning roadmap for the following course:
- Course Code: {unit_code}
- Course Name: {unit_name}
{context}

For each week, provide:
1. A concise title (5-8 words)
2. A brief description (1-2 sentences)
3. 3-5 key topics to be covered
4. A "project_task" (Foundry Task): A specific, practical task or mini-project that applies the week's concepts. This should be something the student can build, write, or solve.

Format your response as a VALID JSON array with exactly 12 weeks. Do not add any text before or after the JSON.
Each week object must use this exact structure:
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
- Covers both theory and practical application

IMPORTANT: Return ONLY the JSON array. valid JSON. [/INST]
"""
        
        headers = {"Authorization": f"Bearer {self.api_key}"}
        payload = {
            "inputs": prompt,
            "parameters": {
                "max_new_tokens": 4096,
                "temperature": 0.7,
                "return_full_text": False
            }
        }

        try:
            logger.info(f"Generating roadmap for: {unit_code} - {unit_name} via Hugging Face")
            
            # Simple retry logic for model loading
            retries = 3
            response_json = None
            
            for i in range(retries):
                response = requests.post(self.API_URL, headers=headers, json=payload)
                if response.status_code == 503: # Model loading
                    logger.info(f"Model loading, waiting... ({i+1}/{retries})")
                    time.sleep(10)
                    continue
                
                if response.status_code != 200:
                    raise Exception(f"API Error {response.status_code}: {response.text}")
                
                response_json = response.json()
                break
            
            if not response_json:
                raise Exception("Failed to get response from Hugging Face")

            generated_text = response_json[0]['generated_text']
            
            # Clean up text to extract JSON
            json_str = generated_text.strip()
            # Find first '[' and last ']'
            start_idx = json_str.find('[')
            end_idx = json_str.rfind(']')
            
            if start_idx != -1 and end_idx != -1:
                json_str = json_str[start_idx:end_idx+1]
            else:
                # Fallback cleanups if model was chatty
                json_str = json_str.replace("```json", "").replace("```", "").strip()
            
            roadmap = json.loads(json_str)
            
            # Validate and fix roadmap
            if len(roadmap) != 12:
                logger.warning(f"Expected 12 weeks, got {len(roadmap)}")
                # Pad or trim logic could go here similar to Gemini service
                # For now just ensuring week numbers
            
            for i, week in enumerate(roadmap):
                week["week"] = i + 1
                if 'project_task' not in week:
                    week['project_task'] = "Review and practice weekly concepts."
            
            return roadmap[:12]
            
        except json.JSONDecodeError as e:
            logger.error(f"Failed to parse HF response as JSON: {str(e)}")
            logger.error(f"Raw text: {generated_text}")
            raise Exception("AI response format error. Please try again.")
        except Exception as e:
            logger.error(f"Hugging Face API error: {str(e)}")
            raise Exception(f"AI service error: {str(e)}")
