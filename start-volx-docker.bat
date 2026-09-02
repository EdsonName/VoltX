@echo off
setlocal EnableExtensions

REM =========================================================
REM VoltX - Iniciador Docker
REM Verifica dependencias, instala se necessario e sobe o ambiente
REM =========================================================

cd /d "%~dp0"

echo =====================================================
echo VoltX - Inicializacao com Docker
echo =====================================================

REM -------------------------------
REM 1) Verifica se o Docker existe
REM -------------------------------
where docker >nul 2>nul
if errorlevel 1 (
    echo [INFO] Docker nao encontrado.
    echo [INFO] Tentando instalar via winget...

    winget install --id Docker.DockerDesktop -e --accept-source-agreements --accept-package-agreements
    if errorlevel 1 (
        echo [ERRO] Falha na instalacao do Docker.
        echo [ERRO] Instale o Docker Desktop manualmente e execute este script novamente.
        echo [ERRO] Link: https://www.docker.com/products/docker-desktop/
        pause
        exit /b 1
    )

    echo [INFO] Docker instalado. Agora abra o Docker Desktop e aguarde o daemon inicializar.
    echo [INFO] Depois pressione qualquer tecla para continuar.
    pause
)

REM -------------------------------
REM 2) Verifica se o Docker daemon esta rodando
REM -------------------------------
docker info >nul 2>nul
if errorlevel 1 (
    echo [WARN] Docker nao esta em execucao.
    echo [INFO] Tentando iniciar o Docker Desktop...

    if exist "%ProgramFiles%\Docker\Docker\Docker Desktop.exe" (
        start "" "%ProgramFiles%\Docker\Docker\Docker Desktop.exe"
    ) else (
        echo [ERRO] Docker Desktop nao foi encontrado no caminho padrao.
        echo [ERRO] Abra o Docker Desktop manualmente e aguarde a inicializacao.
        pause
        exit /b 1
    )

    echo [INFO] Aguardando a inicializacao do Docker...
    timeout /t 20 /nobreak >nul

    docker info >nul 2>nul
    if errorlevel 1 (
        echo [ERRO] Docker ainda nao iniciou corretamente.
        echo [ERRO] Verifique se o Docker Desktop esta em execucao e tente novamente.
        pause
        exit /b 1
    )
)

REM -------------------------------
REM 3) Verifica o Docker Compose
REM -------------------------------
where docker-compose >nul 2>nul
if not errorlevel 1 (
    set "COMPOSE_CMD=docker-compose"
) else (
    docker compose version >nul 2>nul
    if errorlevel 1 (
        echo [ERRO] Docker Compose nao foi encontrado.
        echo [ERRO] Atualize o Docker Desktop ou instale o plugin Compose.
        pause
        exit /b 1
    )
    set "COMPOSE_CMD=docker compose"
)

REM -------------------------------
REM 4) Verifica se a porta 8000 esta livre
REM -------------------------------
for /f "skip=3 tokens=2" %%P in ('netstat -ano ^| findstr /R /C:":8000 " 2^>nul') do (
    if not "%%P"=="" (
        echo [WARN] Porta 8000 ja esta em uso.
        echo [WARN] O projeto usa 8000:80 no docker-compose.yml.
        echo [WARN] Altere a porta em docker-compose.yml para outra, ex.: 8001:80
        echo [WARN] Depois execute este script novamente.
        pause
        exit /b 1
    )
)

REM -------------------------------
REM 5) Subir os containers
REM -------------------------------
echo [INFO] Construindo e iniciando os containers do projeto...
%COMPOSE_CMD% up -d --build
if errorlevel 1 (
    echo [ERRO] Erro ao subir os containers.
    echo [INFO] Exibindo logs para diagnostico:
    %COMPOSE_CMD% logs --tail=100
    pause
    exit /b 1
)

REM -------------------------------
REM 6) Verificacao final
REM -------------------------------
echo.
echo [OK] Ambiente VoltX iniciado com sucesso.
echo [INFO] Acesse: http://localhost:8000
echo [INFO] Banco local: localhost:3306
echo.
echo [INFO] Comandos uteis:
if "%COMPOSE_CMD%"=="docker-compose" (
    echo   docker-compose logs -f
    echo   docker-compose down
    echo   docker-compose restart
) else (
    echo   docker compose logs -f
    echo   docker compose down
    echo   docker compose restart
)

echo.
pause
exit /b 0
