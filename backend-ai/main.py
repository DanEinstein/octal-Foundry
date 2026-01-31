"""
Octal Foundry AI Backend
FastAPI server for roadmap generation and AI coaching
"""

import logging
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware

from config import settings
from models import (
    RoadmapGenerateRequest,
    RoadmapGenerateResponse,
    WeekRoadmap,
    VideoData
)
# from services.gemini_service import GeminiService
# from services.huggingface_service import HuggingFaceService
from services.groq_service import GroqService
# YouTube API removed - AI now generates video recommendations

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

# Create FastAPI app
app = FastAPI(
    title="Octal Foundry AI Backend",
    description="AI-powered learning roadmap generation for university students",
    version="1.0.0"
)

# CORS middleware - allow all origins in development
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Initialize services (lazy loading to handle missing API keys gracefully)
groq_service = None


def get_ai_service():
    """Get or create AI service instance"""
    global groq_service
    if groq_service is None:
        groq_service = GroqService()
    return groq_service


@app.on_event("startup")
async def startup_event():
    """Validate configuration on startup"""
    logger.info("Starting Octal Foundry AI Backend...")
    try:
        settings.validate()
        logger.info("Configuration validated successfully")
    except ValueError as e:
        logger.warning(f"Configuration warning: {e}")
        logger.warning("Some features may not work without proper API keys")


@app.get("/")
def read_root():
    """Health check endpoint"""
    return {
        "status": "Octal Foundry AI Backend Online",
        "version": "1.0.0"
    }


@app.get("/health")
def health_check():
    """Detailed health check"""
    return {
        "status": "healthy",
        "groq_configured": bool(settings.GROQ_API_KEY),
        "environment": settings.ENVIRONMENT
    }


@app.post("/api/roadmap/generate", response_model=RoadmapGenerateResponse)
async def generate_roadmap(request: RoadmapGenerateRequest):
    """
    Generate a 12-week learning roadmap with AI-recommended YouTube videos
    
    Uses Groq AI to generate both roadmap structure AND video recommendations
    (No external YouTube API needed)
    """
    try:
        logger.info(f"Generating roadmap for: {request.unit_code} - {request.unit_name}")
        
        # Validate API keys are configured
        if not settings.GROQ_API_KEY:
            raise HTTPException(
                status_code=503,
                detail="Groq API key not configured"
            )
        
        # Generate roadmap structure with AI (includes video recommendations)
        ai_service = get_ai_service()
        roadmap_structure = ai_service.generate_roadmap(
            unit_code=request.unit_code,
            unit_name=request.unit_name,
            career_path=request.career_path,
            concurrent_units=request.concurrent_units
        )
        
        # Convert to response models
        roadmap = []
        for week_data in roadmap_structure:
            # Convert AI video data to VideoData models
            video_models = []
            for video in week_data.get('videos', []):
                video_models.append(VideoData(
                    video_id=video.get('video_id', 'placeholder'),
                    title=video.get('title', 'Educational Video'),
                    channel=video.get('channel', 'Educational Channel'),
                    thumbnail=video.get('thumbnail', f"https://img.youtube.com/vi/{video.get('video_id', 'placeholder')}/mqdefault.jpg"),
                    duration=video.get('duration', '10:00'),
                    views='',
                    description=''
                ))
            
            # Create week roadmap
            week_roadmap = WeekRoadmap(
                week=week_data['week'],
                title=week_data['title'],
                description=week_data.get('description', ''),
                project_task=week_data.get('project_task', 'Complete weekly exercises.'),
                topics=week_data.get('topics', []),
                videos=video_models
            )
            roadmap.append(week_roadmap)
        
        logger.info(f"Successfully generated roadmap with {len(roadmap)} weeks")
        
        return RoadmapGenerateResponse(
            success=True,
            roadmap=roadmap
        )
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Roadmap generation failed: {str(e)}")
        return RoadmapGenerateResponse(
            success=False,
            error="Failed to generate roadmap",
            details=str(e)
        )


@app.get("/api/coach/hint")
def get_coach_hint():
    """
    Get AI coaching hint (legacy endpoint)
    Currently returns mock data
    """
    return {
        "message": "Your code is missing a BatchNorm2d layer after the first convolution.",
        "layer": "BatchNorm2d",
        "position": "after first Conv2d"
    }


if __name__ == "__main__":
    import uvicorn
    uvicorn.run(
        "main:app",
        host=settings.FASTAPI_HOST,
        port=settings.FASTAPI_PORT,
        reload=settings.is_development()
    )
