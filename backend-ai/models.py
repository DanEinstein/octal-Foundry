"""
Pydantic models for API request/response validation
"""

from pydantic import BaseModel, Field
from typing import List, Optional


class UserPreferences(BaseModel):
    """Optional user preferences for roadmap generation"""
    difficulty_level: str = Field(
        default="intermediate",
        pattern="^(beginner|intermediate|advanced)$"
    )
    learning_style: str = Field(
        default="practical",
        pattern="^(theoretical|practical|mixed)$"
    )


class RoadmapGenerateRequest(BaseModel):
    """Request body for roadmap generation endpoint"""
    unit_code: str = Field(..., min_length=3, max_length=50)
    unit_name: str = Field(..., min_length=5, max_length=255)
    lecturer_name: Optional[str] = Field(None, max_length=255)
    semester: Optional[str] = Field(None, max_length=50)
    year: Optional[int] = Field(None, ge=2020, le=2030)
    career_path: Optional[str] = Field(None, max_length=255)
    concurrent_units: Optional[List[str]] = Field(default_factory=list)
    user_preferences: Optional[UserPreferences] = None


class VideoData(BaseModel):
    """YouTube video metadata"""
    video_id: str
    title: str
    channel: str
    thumbnail: str
    duration: str
    views: str
    description: str


class WeekRoadmap(BaseModel):
    """Single week in the 12-week roadmap"""
    week: int = Field(..., ge=1, le=12)
    title: str
    description: str
    project_task: str
    topics: List[str] = Field(..., min_length=1, max_length=10)
    videos: List[VideoData] = Field(default_factory=list)


class RoadmapGenerateResponse(BaseModel):
    """Response from roadmap generation endpoint"""
    success: bool
    roadmap: Optional[List[WeekRoadmap]] = None
    error: Optional[str] = None
    details: Optional[str] = None
