"""
YouTube Data API Service for searching educational videos
"""

import logging
import re
from typing import List, Dict
from googleapiclient.discovery import build
from googleapiclient.errors import HttpError

import sys
sys.path.append('..')
from config import settings

logger = logging.getLogger(__name__)


class YouTubeService:
    """Service for interacting with YouTube Data API v3"""
    
    def __init__(self):
        """Initialize YouTube API client"""
        self.youtube = build('youtube', 'v3', developerKey=settings.YOUTUBE_API_KEY)
    
    def search_videos(self, query: str, max_results: int = 4) -> List[Dict]:
        """
        Search YouTube for educational videos
        
        Args:
            query: Search query (e.g., "Introduction to Machine Learning tutorial")
            max_results: Number of videos to return (default 4)
        
        Returns:
            List of video metadata dictionaries
        
        Raises:
            Exception: If API call fails
        """
        try:
            logger.info(f"Searching YouTube for: {query}")
            
            # Search for videos with educational focus
            search_response = self.youtube.search().list(
                q=f"{query} tutorial",
                part='id,snippet',
                type='video',
                maxResults=max_results,
                order='relevance',
                videoDuration='medium',  # 4-20 minutes
                relevanceLanguage='en',
                safeSearch='strict'
            ).execute()
            
            video_ids = [
                item['id']['videoId'] 
                for item in search_response.get('items', [])
            ]
            
            if not video_ids:
                logger.warning(f"No videos found for: {query}")
                return []
            
            # Get detailed video information
            videos_response = self.youtube.videos().list(
                part='snippet,contentDetails,statistics',
                id=','.join(video_ids)
            ).execute()
            
            videos = []
            for item in videos_response.get('items', []):
                video_data = {
                    'video_id': item['id'],
                    'title': item['snippet']['title'],
                    'channel': item['snippet']['channelTitle'],
                    'thumbnail': item['snippet']['thumbnails'].get('high', {}).get(
                        'url', 
                        item['snippet']['thumbnails'].get('default', {}).get('url', '')
                    ),
                    'duration': self._format_duration(item['contentDetails']['duration']),
                    'views': self._format_views(item['statistics'].get('viewCount', '0')),
                    'description': self._truncate_description(item['snippet']['description'])
                }
                videos.append(video_data)
            
            logger.info(f"Found {len(videos)} videos for: {query}")
            return videos
            
        except HttpError as e:
            logger.error(f"YouTube API HTTP error: {str(e)}")
            raise Exception(f"YouTube API error: {str(e)}")
        except Exception as e:
            logger.error(f"YouTube service error: {str(e)}")
            raise Exception(f"Video search failed: {str(e)}")
    
    def _format_duration(self, duration: str) -> str:
        """
        Convert ISO 8601 duration to readable format
        
        Args:
            duration: ISO 8601 duration string (e.g., "PT19M13S")
        
        Returns:
            Formatted string (e.g., "19:13")
        """
        match = re.match(r'PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?', duration)
        if not match:
            return "0:00"
        
        hours, minutes, seconds = match.groups()
        hours = int(hours) if hours else 0
        minutes = int(minutes) if minutes else 0
        seconds = int(seconds) if seconds else 0
        
        if hours > 0:
            return f"{hours}:{minutes:02d}:{seconds:02d}"
        else:
            return f"{minutes}:{seconds:02d}"
    
    def _format_views(self, views: str) -> str:
        """
        Format view count to human-readable format
        
        Args:
            views: View count as string (e.g., "18234567")
        
        Returns:
            Formatted string (e.g., "18M")
        """
        try:
            views_int = int(views)
            if views_int >= 1_000_000:
                return f"{views_int // 1_000_000}M"
            elif views_int >= 1_000:
                return f"{views_int // 1_000}K"
            else:
                return str(views_int)
        except ValueError:
            return "0"
    
    def _truncate_description(self, description: str, max_length: int = 200) -> str:
        """
        Truncate description to max length
        
        Args:
            description: Full description text
            max_length: Maximum characters to keep
        
        Returns:
            Truncated description with ellipsis if needed
        """
        if len(description) <= max_length:
            return description
        return description[:max_length].rsplit(' ', 1)[0] + '...'
