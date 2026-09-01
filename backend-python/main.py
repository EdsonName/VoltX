from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from config import API_TITLE, API_VERSION, API_DESCRIPTION, HOST, PORT, DEBUG

app = FastAPI(
    title=API_TITLE,
    version=API_VERSION,
    description=API_DESCRIPTION
)

# CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/")
def read_root():
    return {"message": "VolX Python Backend - FastAPI", "version": API_VERSION}

@app.get("/health")
def health_check():
    return {"status": "healthy"}

@app.get("/orcamentos")
def listar_orcamentos():
    return {"orcamentos": []}

@app.post("/orcamentos")
def criar_orcamento():
    return {"message": "Orçamento criado com sucesso"}

@app.get("/agendamentos")
def listar_agendamentos():
    return {"agendamentos": []}

@app.post("/agendamentos")
def criar_agendamento():
    return {"message": "Agendamento criado com sucesso"}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(
        "main:app",
        host=HOST,
        port=PORT,
        reload=DEBUG
    )
