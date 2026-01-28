from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

app = FastAPI()

origins = [
    "http://localhost",
    "http://localhost:8080",
]

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"], # Allow all for now to avoid issues in dev
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/")
def read_root():
    return {"status": "Octal Foundry AI Backend Online"}

@app.get("/api/coach/hint")
def get_coach_hint():
    return {
        "message": "Your code is missing a BatchNorm2d layer after the first convolution.",
        "layer": "BatchNorm2d",
        "position": "after first Conv2d"
    }
