# Octal Foundry

An AI-powered learning roadmap generator for university students.

## Project Structure
- `backend-ai/`: FastAPI backend (Python) handling AI logic.
- `frontend-php/`: PHP frontend application.
- `database/`: SQL scripts.
- `venv/`: Shared Python virtual environment.

## How to Run

You need to run **both** the backend and frontend in separate terminal windows.

### 1. Start the Backend (FastAPI)
This handles the AI roadmap generation.

```bash
# Activate virtual environment
source venv/bin/activate

# Navigate to backend directory
cd backend-ai

# Start the server (runs on port 8000)
uvicorn main:app --host 0.0.0.0 --port 8000 --reload
```

### 2. Start the Frontend (PHP)
This is the user interface.

```bash
# Navigate to frontend directory
cd frontend-php

# Start the built-in PHP server (runs on port 8080)
php -S localhost:8080
```

### Accessing the App
Open your browser and navigate to:
[http://localhost:8080](http://localhost:8080)
