import subprocess
import os
import time
import pygetwindow as gw  # Instala con: pip install pygetwindow

def start_xampp_apache():
    # Ruta al ejecutable de XAMPP y apache_start.bat
    xampp_path = "C:\\xampp\\xampp-control.exe"
    apache_start_path = "C:\\xampp\\apache_start.bat"
    
    if os.path.exists(xampp_path):
        try:
            print("Iniciando Apache...")
            # Ejecuta apache_start.bat para iniciar Apache
            subprocess.Popen([apache_start_path], shell=True)
            time.sleep(5)  # Tiempo para que Apache inicie
            
            print("Abriendo XAMPP Control Panel...")
            xampp_process = subprocess.Popen([xampp_path])  # Inicia XAMPP Control Panel
            time.sleep(3)  # Tiempo para que la ventana se abra
            
            # Cerrar la ventana de XAMPP Control Panel sin detener Apache
            windows = gw.getWindowsWithTitle("XAMPP Control Panel")
            if windows:
                windows[0].close()
                print("XAMPP Control Panel cerrado, Apache sigue activo.")
            else:
                print("No se pudo encontrar la ventana de XAMPP.")
        except Exception as e:
            print(f"Error al iniciar XAMPP o Apache: {e}")
    else:
        print("No se encontró XAMPP en la ruta especificada. Verifica la ruta.")

if __name__ == "__main__":
    start_xampp_apache()