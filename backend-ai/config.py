"""
Configuration manager for Octal Foundry AI Backend
Loads environment variables from .env file
"""

import os
from dotenv import load_dotenv

load_dotenv()


class Settings:
    """Application settings loaded from environment variables"""
    
    GEMINI_API_KEY: str = os.getenv("GEMINI_API_KEY", "")
    HUGGINGFACE_API_KEY: str = os.getenv("HUGGINGFACE_API_KEY", "")
    GROQ_API_KEY: str = os.getenv("GROQ_API_KEY", "")
    YOUTUBE_API_KEY: str = os.getenv("YOUTUBE_API_KEY", "")
    FASTAPI_HOST: str = os.getenv("FASTAPI_HOST", "0.0.0.0")
    FASTAPI_PORT: int = int(os.getenv("FASTAPI_PORT", "8000"))
    ENVIRONMENT: str = os.getenv("ENVIRONMENT", "development")
    
    def validate(self):
        """Validate required environment variables are set"""
        missing = []
        # Check for AI provider (Groq is primary)
        if not self.GROQ_API_KEY and not self.HUGGINGFACE_API_KEY and not self.GEMINI_API_KEY:
             missing.append("GROQ_API_KEY (or HUGGINGFACE_API_KEY or GEMINI_API_KEY)")
        
        # YouTube API is optional - AI now generates video recommendations
        
        if missing:
            raise ValueError(f"Missing required environment variables: {', '.join(missing)}")
    
    def is_development(self) -> bool:
        """Check if running in development mode"""
        return self.ENVIRONMENT == "development"


settings = Settings()
