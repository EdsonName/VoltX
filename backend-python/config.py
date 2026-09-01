import os
from dotenv import load_dotenv

load_dotenv()

# Database
DATABASE_URL = os.getenv("DATABASE_URL", "mysql+mysqlconnector://root:root@localhost:3306/volx_db")

# API
API_TITLE = "VolX API - Python Backend"
API_VERSION = "1.0.0"
API_DESCRIPTION = "API para processamento de dados, workers e serviços assincronos"

# Server
DEBUG = os.getenv("DEBUG", "True") == "True"
HOST = os.getenv("HOST", "0.0.0.0")
PORT = int(os.getenv("PORT", 8001))
