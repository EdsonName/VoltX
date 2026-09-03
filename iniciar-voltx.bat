@echo off
setlocal EnableExtensions
title VoltX - Preparar e iniciar servidor
cd /d "%~dp0"

echo [VoltX] Verificando Docker...
where docker >nul 2>&1
if errorlevel 1 goto instalar_docker
goto verificar_daemon

:instalar_docker
echo [VoltX] Docker nao encontrado. Verificando Windows Package Manager...
where winget >nul 2>&1
if errorlevel 1 (
    echo [ERRO] Winget nao foi encontrado.
    echo Instale o App Installer pela Microsoft Store e execute este arquivo novamente.
    pause
    exit /b 1
)
echo [VoltX] Instalando Docker Desktop. O Windows pode solicitar permissao de administrador.
winget install --exact --id Docker.DockerDesktop --accept-package-agreements --accept-source-agreements
if errorlevel 1 (
    echo [ERRO] Nao foi possivel instalar o Docker Desktop automaticamente.
    pause
    exit /b 1
)
set "PATH=%PATH%;C:\Program Files\Docker\Docker\resources\bin"

:verificar_daemon
docker info >nul 2>&1
if not errorlevel 1 goto verificar_compose
echo [VoltX] Iniciando Docker Desktop...
if exist "C:\Program Files\Docker\Docker\Docker Desktop.exe" start "" "C:\Program Files\Docker\Docker\Docker Desktop.exe"
echo [VoltX] Aguardando o mecanismo do Docker ficar disponivel...
set /a VOLTX_TENTATIVAS=0
:aguardar_docker
docker info >nul 2>&1
if not errorlevel 1 goto verificar_compose
set /a VOLTX_TENTATIVAS+=1
if %VOLTX_TENTATIVAS% GEQ 90 (
    echo [ERRO] O Docker nao iniciou dentro do tempo esperado.
    echo Abra o Docker Desktop, conclua a configuracao inicial e tente novamente.
    pause
    exit /b 1
)
timeout /t 2 /nobreak >nul
goto aguardar_docker

:verificar_compose
docker compose version >nul 2>&1
if errorlevel 1 (
    echo [ERRO] O plugin Docker Compose nao esta disponivel.
    echo Atualize ou reinstale o Docker Desktop.
    pause
    exit /b 1
)

echo [VoltX] Construindo e iniciando os containers...
docker compose up -d --build
if errorlevel 1 (
    echo [ERRO] Falha ao iniciar os containers. Consulte as mensagens acima.
    pause
    exit /b 1
)

echo [VoltX] Aguardando o site responder em http://localhost:8000 ...
set /a VOLTX_SITE_TENTATIVAS=0
:aguardar_site
powershell -NoProfile -Command "try { $r=Invoke-WebRequest -UseBasicParsing -TimeoutSec 3 http://localhost:8000/; if ($r.StatusCode -ge 200 -and $r.StatusCode -lt 500) { exit 0 } } catch {}; exit 1" >nul 2>&1
if not errorlevel 1 goto servidor_pronto
set /a VOLTX_SITE_TENTATIVAS+=1
if %VOLTX_SITE_TENTATIVAS% GEQ 30 (
    echo [AVISO] Os containers iniciaram, mas o site ainda nao respondeu.
    docker compose ps
    pause
    exit /b 1
)
timeout /t 2 /nobreak >nul
goto aguardar_site

:servidor_pronto
echo.
echo [OK] VoltX esta disponivel em http://localhost:8000
docker compose ps
start "" "http://localhost:8000"
echo.
echo Para desligar o servidor posteriormente, execute: docker compose down
pause
exit /b 0
