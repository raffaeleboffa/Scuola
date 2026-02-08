import halocode
import event
import urequests
import network
import time

# --- CONFIGURAZIONE GLOBALE ---
PC_IP = "10.18.254.10"
SERVER_PORT = 5000
SELECTED_PROG = "vola1" # Inizia con vola1

# --- GESTIONE WI-FI (Non modificata) ---
@event.start
def on_start():
    halocode.wifi.start(ssid = 'boffarouter', password = 'sommer123', mode = halocode.wifi.WLAN_MODE_STA)
    while not halocode.wifi.is_connected():
        halocode.led.show_all(255,0,0) # Rosso: In attesa connessione
        pass

    halocode.led.show_all(0, 255, 0) # Verde: Connesso (colore distintivo)
    time.sleep(2)
    halocode.led.off_all()

# --- TRIGGER PULSANTE (INVIA COMANDO) (Non modificata nella logica) ---
@event.button_pressed
def on_button_pressed():
    global SELECTED_PROG
    
    # Ricostruiamo l'URL aggiornato
    url_to_send = "http://" + PC_IP + ":" + str(SERVER_PORT) + "/" + SELECTED_PROG
    
    halocode.led.show_all(0, 0, 255) # Blu: Invio in corso
    
    try:
        response = urequests.get(url_to_send) 
        response.close()
        
        # Successo: Blink Verde Veloce
        for _ in range(2):
            halocode.led.show_all(0, 255, 0)
            time.sleep(0.1)
            halocode.led.off_all()
            time.sleep(0.1)
        
    except Exception as e:
        # Errore: Blink Rosso Veloce
        for _ in range(2):
            halocode.led.show_all(255, 0, 0)
            time.sleep(0.1)
            halocode.led.off_all()
            time.sleep(0.1)
        
    halocode.led.off_all()

# --- CAMBIO PROGRAMMA (TOUCHPADS) con colori distintivi ---

@event.touchpad0_active
def on_touchpad0_active():
    global SELECTED_PROG 
    SELECTED_PROG = "vola1"
    
    # 🔴 VOLA 1: ROSSO (Facile da vedere)
    halocode.led.show_all(255, 0, 0) 
    time.sleep(0.5)
    halocode.led.off_all()
    
@event.touchpad1_active
def on_touchpad1_active():
    global SELECTED_PROG
    SELECTED_PROG = "vola2"
    
    # 🟢 VOLA 2: VERDE (Colore complementare)
    halocode.led.show_all(0, 255, 0) 
    time.sleep(0.5)
    halocode.led.off_all()
    
@event.touchpad2_active
def on_touchpad2_active():
    global SELECTED_PROG
    SELECTED_PROG = "vola3"
    
    # 🔵 VOLA 3: BLU (Colore primario)
    halocode.led.show_all(0, 0, 255)
    time.sleep(0.5)
    halocode.led.off_all()
    
@event.touchpad3_active
def on_touchpad3_active():
    global SELECTED_PROG
    SELECTED_PROG = "vola4"
    
    # 🟡 VOLA 4: GIALLO (Mix di R e G, molto visibile)
    halocode.led.show_all(255, 255, 0) 
    time.sleep(0.5)
    halocode.led.off_all()