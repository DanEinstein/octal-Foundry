"""
Video Agent Service - LangChain-based Content Judge
Uses Groq LLM to generate contextually relevant YouTube video recommendations
based on student's career path and unit syllabus.

Uses a curated database of VERIFIED YouTube video IDs to ensure videos are playable.
"""

import json
import logging
import random
from typing import List, Dict, Optional

from langchain_groq import ChatGroq
from langchain_core.prompts import ChatPromptTemplate
from langchain_core.output_parsers import JsonOutputParser

import sys
sys.path.append('..')
from config import settings

logger = logging.getLogger(__name__)


# =============================================================================
# VERIFIED VIDEO DATABASE
# These are REAL YouTube video IDs that have been verified to exist.
# Organized by topic/channel for easy selection.
# =============================================================================

VERIFIED_VIDEOS = {
    # 3Blue1Brown - Math & Linear Algebra
    "linear_algebra": [
        {"video_id": "fNk_zzaMoSs", "title": "Vectors | Chapter 1, Essence of linear algebra", "channel": "3Blue1Brown", "duration": "9:52"},
        {"video_id": "k7RM-ot2NWY", "title": "Linear combinations, span, and basis vectors", "channel": "3Blue1Brown", "duration": "9:59"},
        {"video_id": "kYB8IZa5AuE", "title": "Linear transformations and matrices", "channel": "3Blue1Brown", "duration": "10:58"},
        {"video_id": "XkY2DOUCWMU", "title": "Matrix multiplication as composition", "channel": "3Blue1Brown", "duration": "10:03"},
        {"video_id": "rHLEWRxRGiM", "title": "Three-dimensional linear transformations", "channel": "3Blue1Brown", "duration": "4:46"},
        {"video_id": "Ip3X9LOh2dk", "title": "The determinant", "channel": "3Blue1Brown", "duration": "10:03"},
        {"video_id": "uQhTuRlWMxw", "title": "Inverse matrices, column space and null space", "channel": "3Blue1Brown", "duration": "12:08"},
        {"video_id": "PFDu9oVAE-g", "title": "Eigenvectors and eigenvalues", "channel": "3Blue1Brown", "duration": "17:15"},
    ],
    
    # Python Programming
    "python_basics": [
        {"video_id": "rfscVS0vtbw", "title": "Learn Python - Full Course for Beginners", "channel": "freeCodeCamp", "duration": "4:26:52"},
        {"video_id": "_uQrJ0TkZlc", "title": "Python Tutorial - Python Full Course for Beginners", "channel": "Programming with Mosh", "duration": "6:14:07"},
        {"video_id": "8DvywoWv6fI", "title": "Python for Beginners - Learn Python in 1 Hour", "channel": "Programming with Mosh", "duration": "1:00:05"},
        {"video_id": "Z1Yd7upQsXY", "title": "Python Functions", "channel": "Corey Schafer", "duration": "21:48"},
        {"video_id": "9Os0o3wzS_I", "title": "Python OOP Tutorial 1: Classes and Instances", "channel": "Corey Schafer", "duration": "15:24"},
        {"video_id": "3ohzBxoFHAY", "title": "Python Tutorial: if __name__ == '__main__'", "channel": "Corey Schafer", "duration": "8:43"},
    ],
    
    # Data Science & Pandas
    "data_science": [
        {"video_id": "vmEHCJofslg", "title": "Pandas Tutorial", "channel": "Corey Schafer", "duration": "1:00:35"},
        {"video_id": "ZyhVh-qRZPA", "title": "Python Pandas Tutorial (Complete Guide)", "channel": "Keith Galli", "duration": "1:00:27"},
        {"video_id": "GPVsHOlRBBI", "title": "NumPy Tutorial", "channel": "freeCodeCamp", "duration": "58:09"},
        {"video_id": "LHBE6Q9XlzI", "title": "Python Data Science Tutorial: Analyzing Data", "channel": "freeCodeCamp", "duration": "2:02:18"},
        {"video_id": "r-uOLxNrNk8", "title": "Data Analysis with Python - Full Course", "channel": "freeCodeCamp", "duration": "4:22:13"},
        {"video_id": "ua-CiDNNj30", "title": "Matplotlib Tutorial", "channel": "Corey Schafer", "duration": "32:33"},
    ],
    
    # Machine Learning
    "machine_learning": [
        {"video_id": "Gv9_4yMHFhI", "title": "Machine Learning Tutorial Python - 1", "channel": "codebasics", "duration": "20:50"},
        {"video_id": "aircAruvnKk", "title": "But what is a neural network?", "channel": "3Blue1Brown", "duration": "19:13"},
        {"video_id": "IHZwWFHWa-w", "title": "Gradient descent, how neural networks learn", "channel": "3Blue1Brown", "duration": "21:01"},
        {"video_id": "tIeHLnjs5U8", "title": "TensorFlow 2.0 Complete Course", "channel": "freeCodeCamp", "duration": "6:54:12"},
        {"video_id": "WFr2WgN9_xE", "title": "PyTorch for Deep Learning - Full Course", "channel": "freeCodeCamp", "duration": "25:37:26"},
        {"video_id": "7eh4d6sabA0", "title": "Machine Learning Course for Beginners", "channel": "freeCodeCamp", "duration": "9:52:19"},
    ],
    
    # Web Development
    "web_development": [
        {"video_id": "pQN-pnXPaVg", "title": "HTML Full Course - Build a Website Tutorial", "channel": "freeCodeCamp", "duration": "2:02:31"},
        {"video_id": "1Rs2ND1ryYc", "title": "CSS Tutorial - Zero to Hero", "channel": "freeCodeCamp", "duration": "6:18:37"},
        {"video_id": "PkZNo7MFNFg", "title": "Learn JavaScript - Full Course for Beginners", "channel": "freeCodeCamp", "duration": "3:26:42"},
        {"video_id": "Ke90Tje7VS0", "title": "React JS - React Tutorial for Beginners", "channel": "Programming with Mosh", "duration": "1:18:43"},
        {"video_id": "SccSCuHhOw0", "title": "Node.js and Express.js - Full Course", "channel": "freeCodeCamp", "duration": "8:16:48"},
        {"video_id": "Oe421EPjeBE", "title": "Node.js / Express Course - Build 4 Projects", "channel": "freeCodeCamp", "duration": "10:00:08"},
    ],
    
    # Computer Graphics & Game Dev
    "computer_graphics": [
        {"video_id": "kfM-yu0iQBk", "title": "OpenGL Course - Create 3D and 2D Graphics", "channel": "freeCodeCamp", "duration": "1:46:24"},
        {"video_id": "45MIykWJ-C4", "title": "Game Development with Unity", "channel": "freeCodeCamp", "duration": "2:28:43"},
        {"video_id": "pwZpJzpE2lQ", "title": "Godot Game Engine Tutorial", "channel": "freeCodeCamp", "duration": "3:46:27"},
        {"video_id": "vLJf-KKPLmg", "title": "Quaternions and 3D Rotation", "channel": "3Blue1Brown", "duration": "31:50"},
        {"video_id": "d4EgbgTm0Bg", "title": "A Brief History of Graphics", "channel": "Ahoy", "duration": "49:22"},
        {"video_id": "C8YRGuzF9Bw", "title": "3D Math for Game Developers", "channel": "Brackeys", "duration": "13:50"},
    ],
    
    # Statistics
    "statistics": [
        {"video_id": "xxpc-HPKN28", "title": "StatQuest: Histograms, Clearly Explained", "channel": "StatQuest", "duration": "3:42"},
        {"video_id": "oI3hZJqXJuc", "title": "StatQuest: Standard Deviation", "channel": "StatQuest", "duration": "9:42"},
        {"video_id": "Kdsp6soqA7o", "title": "StatQuest: P Values, clearly explained", "channel": "StatQuest", "duration": "11:21"},
        {"video_id": "vikkiwjQqfU", "title": "StatQuest: Probability vs Likelihood", "channel": "StatQuest", "duration": "5:01"},
        {"video_id": "Q8l2WGYwb2U", "title": "StatQuest: Linear Regression, Clearly Explained", "channel": "StatQuest", "duration": "27:07"},
        {"video_id": "yIYKR4sgzI8", "title": "StatQuest: Logistic Regression", "channel": "StatQuest", "duration": "8:47"},
    ],
    
    # Algorithms & Data Structures
    "algorithms": [
        {"video_id": "8hly31xKli0", "title": "Algorithms and Data Structures Tutorial", "channel": "freeCodeCamp", "duration": "5:22:09"},
        {"video_id": "RBSGKlAvoiM", "title": "Data Structures Easy to Advanced Course", "channel": "freeCodeCamp", "duration": "8:03:24"},
        {"video_id": "kPRA0W1kECg", "title": "Big O Notation", "channel": "freeCodeCamp", "duration": "12:15"},
        {"video_id": "DuDz6B4cqVc", "title": "Sorting Algorithms Explained Visually", "channel": "freeCodeCamp", "duration": "10:01"},
        {"video_id": "F5Q3RL2-qME", "title": "Binary Tree Algorithms for Technical Interviews", "channel": "freeCodeCamp", "duration": "2:21:11"},
    ],
    
    # General Programming
    "general": [
        {"video_id": "zOjov-2OZ0E", "title": "Learn Git in 15 Minutes", "channel": "Colt Steele", "duration": "15:14"},
        {"video_id": "RGOj5yH7evk", "title": "Git and GitHub for Beginners - Crash Course", "channel": "freeCodeCamp", "duration": "1:08:29"},
        {"video_id": "8jLOx1hD3_o", "title": "C++ Programming Course - Beginner to Advanced", "channel": "freeCodeCamp", "duration": "31:24:38"},
        {"video_id": "KJgsSFOSQv0", "title": "C Programming Tutorial for Beginners", "channel": "freeCodeCamp", "duration": "3:46:13"},
        {"video_id": "grEKMHGYyns", "title": "Java Full Course", "channel": "Bro Code", "duration": "12:00:00"},
    ],
}


def get_topic_category(topic: str, career_path: str) -> str:
    """Determine the best video category based on topic and career path."""
    topic_lower = topic.lower()
    career_lower = career_path.lower()
    
    # Check for specific topic keywords
    if any(kw in topic_lower for kw in ['linear algebra', 'matrix', 'vector', 'eigenvalue', 'determinant']):
        return 'linear_algebra'
    if any(kw in topic_lower for kw in ['python', 'function', 'class', 'module', 'variable']):
        return 'python_basics'
    if any(kw in topic_lower for kw in ['data analysis', 'pandas', 'numpy', 'visualization', 'matplotlib', 'data science', 'dataframe']):
        return 'data_science'
    if any(kw in topic_lower for kw in ['machine learning', 'neural network', 'deep learning', 'tensorflow', 'pytorch', 'model training']):
        return 'machine_learning'
    if any(kw in topic_lower for kw in ['html', 'css', 'javascript', 'react', 'web', 'frontend', 'backend', 'node']):
        return 'web_development'
    if any(kw in topic_lower for kw in ['graphics', '3d', 'opengl', 'shader', 'game', 'unity', 'godot', 'render']):
        return 'computer_graphics'
    if any(kw in topic_lower for kw in ['statistic', 'probability', 'distribution', 'regression', 'hypothesis']):
        return 'statistics'
    if any(kw in topic_lower for kw in ['algorithm', 'data structure', 'sorting', 'tree', 'graph', 'search']):
        return 'algorithms'
    
    # Fall back to career path
    if 'data' in career_lower or 'science' in career_lower or 'analytics' in career_lower:
        return 'data_science'
    if 'web' in career_lower or 'frontend' in career_lower or 'fullstack' in career_lower:
        return 'web_development'
    if 'graphics' in career_lower or 'game' in career_lower:
        return 'computer_graphics'
    if 'machine learning' in career_lower or 'ai' in career_lower or 'ml' in career_lower:
        return 'machine_learning'
    
    return 'general'


class VideoAgent:
    """
    LangChain-based Content Judge for generating career-focused 
    YouTube video recommendations using VERIFIED video IDs.
    """
    
    def __init__(self):
        """Initialize the Video Agent with Groq LLM."""
        self.llm = ChatGroq(
            api_key=settings.GROQ_API_KEY,
            model_name="llama-3.3-70b-versatile",
            temperature=0.7,
            max_tokens=2048
        )
        
        self.prompt = ChatPromptTemplate.from_messages([
            ("system", self._get_system_prompt()),
            ("user", self._get_user_prompt())
        ])
        
        self.parser = JsonOutputParser()
        self.chain = self.prompt | self.llm | self.parser
    
    def _get_system_prompt(self) -> str:
        """Return the system prompt for the Content Judge."""
        return """You are an expert educational video curator. Your role is to select and rank 
YouTube videos from a provided list based on their relevance to the student's learning context.

You will be given a list of VERIFIED videos. Your job is to:
1. Select the 2-3 MOST RELEVANT videos from the list
2. Rank them by relevance to the specific topic and career path
3. Explain why each video is relevant

Always respond with valid JSON only."""
    
    def _get_user_prompt(self) -> str:
        """Return the user prompt template."""
        return """Select the best videos for this learning context:

**Unit Name:** {unit_name}
**Career Path:** {career_path}  
**Week Topic:** {week_topic}

**Available Videos (VERIFIED to exist):**
{available_videos}

Select 2-3 videos that are MOST relevant for a {career_path} student learning "{week_topic}".
Rank by relevance and explain why each is useful.

Return a JSON array with your selections (use the EXACT video_id from the list above):
[
  {{
    "video_id": "exact_id_from_list",
    "title": "video title",
    "channel": "channel name",
    "duration": "duration",
    "relevance_note": "Why this video is perfect for learning {week_topic}"
  }}
]"""
    
    def get_contextual_videos(
        self, 
        unit_name: str, 
        career_path: str, 
        week_topic: str
    ) -> List[Dict]:
        """
        Generate contextually relevant video recommendations from VERIFIED database.
        """
        try:
            logger.info(f"Getting contextual videos for: {week_topic} ({career_path})")
            
            # Get the appropriate category of videos
            category = get_topic_category(week_topic, career_path)
            available_videos = VERIFIED_VIDEOS.get(category, VERIFIED_VIDEOS['general'])
            
            # Add some variety by including related categories
            if category == 'data_science':
                available_videos = available_videos + random.sample(VERIFIED_VIDEOS.get('python_basics', []), min(2, len(VERIFIED_VIDEOS.get('python_basics', []))))
            elif category == 'machine_learning':
                available_videos = available_videos + random.sample(VERIFIED_VIDEOS.get('data_science', []), min(2, len(VERIFIED_VIDEOS.get('data_science', []))))
            
            # Format videos for prompt
            videos_text = "\n".join([
                f"- ID: {v['video_id']} | Title: {v['title']} | Channel: {v['channel']} | Duration: {v['duration']}"
                for v in available_videos
            ])
            
            # Try LLM selection first
            try:
                result = self.chain.invoke({
                    "unit_name": unit_name,
                    "career_path": career_path,
                    "week_topic": week_topic,
                    "available_videos": videos_text
                })
                
                if isinstance(result, list) and len(result) > 0:
                    # Validate that returned video IDs exist in our database
                    valid_ids = {v['video_id'] for v in available_videos}
                    validated_videos = []
                    
                    for video in result:
                        if isinstance(video, dict) and video.get('video_id') in valid_ids:
                            # Get full video data from our database
                            full_video = next((v for v in available_videos if v['video_id'] == video['video_id']), None)
                            if full_video:
                                validated_videos.append({
                                    'video_id': full_video['video_id'],
                                    'title': full_video['title'],
                                    'channel': full_video['channel'],
                                    'duration': full_video['duration'],
                                    'thumbnail': f"https://img.youtube.com/vi/{full_video['video_id']}/mqdefault.jpg",
                                    'relevance_note': video.get('relevance_note', '')
                                })
                    
                    if validated_videos:
                        logger.info(f"LLM selected {len(validated_videos)} verified videos for {week_topic}")
                        return validated_videos[:3]
            
            except Exception as e:
                logger.warning(f"LLM selection failed, using random selection: {str(e)}")
            
            # Fallback: Random selection from verified videos
            selected = random.sample(available_videos, min(3, len(available_videos)))
            videos = []
            for v in selected:
                videos.append({
                    'video_id': v['video_id'],
                    'title': v['title'],
                    'channel': v['channel'],
                    'duration': v['duration'],
                    'thumbnail': f"https://img.youtube.com/vi/{v['video_id']}/mqdefault.jpg",
                    'relevance_note': f"Recommended for {career_path} students"
                })
            
            logger.info(f"Generated {len(videos)} verified videos for {week_topic}")
            return videos
            
        except Exception as e:
            logger.error(f"Video agent error: {str(e)}")
            return []
    
    def validate_video(
        self,
        unit_name: str,
        career_path: str,
        week_topic: str,
        candidate_metadata: Dict
    ) -> Dict:
        """Validate a single video candidate against the learning context."""
        validation_prompt = ChatPromptTemplate.from_messages([
            ("system", """You are a Content Judge that validates YouTube videos for educational relevance.
Respond with JSON: {"approved": true/false, "reason": "brief explanation"}"""),
            ("user", """Validate this video for a {career_path} student learning {week_topic}:
- Title: {video_title}
- Description: {video_description}

Return JSON: {{"approved": true/false, "reason": "why approved or rejected"}}""")
        ])
        
        try:
            chain = validation_prompt | self.llm | self.parser
            result = chain.invoke({
                "unit_name": unit_name,
                "career_path": career_path,
                "week_topic": week_topic,
                "video_title": candidate_metadata.get('title', ''),
                "video_description": candidate_metadata.get('description', '')
            })
            
            return {
                "approved": result.get("approved", False),
                "reason": result.get("reason", "No reason provided")
            }
            
        except Exception as e:
            logger.error(f"Video validation error: {str(e)}")
            return {"approved": False, "reason": f"Validation error: {str(e)}"}


# Singleton instance
_video_agent = None

def get_video_agent() -> VideoAgent:
    """Get or create the video agent singleton."""
    global _video_agent
    if _video_agent is None:
        _video_agent = VideoAgent()
    return _video_agent
