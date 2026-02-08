from flask import Flask, request, jsonify
from djitellopy import Tello
import time
import threading

app = Flask(__name__)
tello1 = Tello(host='10.18.254.249') # L'ip del tello 1 ElonMusk 14
tello2 = Tello(host='10.18.254.122') # L'ip del tello 2 ElonMusk 13
PC_IP_ADDRESS = "10.18.254.10"  # L'ip del PC
SERVER_PORT = 5000

def get_battery_level(tello, drone_number):
    try:
        battery = tello.get_battery()
        print(f"Batteria Tello {drone_number}: {battery}%")
    except:
        print(f"Impossibile ottenere il livello della batteria del Tello {drone_number}.")

def vola1(): # ElonMusk 14
    print("PIN selezionato: 0")
    print("Tentativo di connessione al tello1...")
    
    try:
        tello1.connect()
        battery = tello1.get_battery()
        print(f"Connessione tello1 Riuscita. Batteria: {battery}%")

        if battery < 30:
            print("ATTENZIONE: Batteria bassa. Aborto il volo.")
            return

        print("Avvio sequenza di volo...")
        tello1.takeoff()

        tello1.move_up(100)

        tello1.move_forward(250)
        
        tello1.flip_forward()
        get_battery_level(tello1, 14)

        tello1.move_forward(250)
        
        tello1.flip_forward()
        get_battery_level(tello1, 14)

        tello1.rotate_clockwise(180)
        
        tello1.flip_forward()
        get_battery_level(tello1, 14)

        tello1.move_forward(500)

        tello1.land()
        get_battery_level(tello1, 14)

        print("Sequenza di volo completata.")

    except Exception as e:
        print(f"ERRORE GRAVE nel volo: {e}")
        try:
            tello1.land()
        except:
            pass
    finally:
        print("Il drone ha terminato l'operazione.")

def vola2(): # ElonMusk 13
    print("PIN selezionato: 1")
    print("Tentativo di connessione al tello2...")
    
    try:
        tello2.connect()
        battery = tello2.get_battery()
        print(f"Connessione tello2 Riuscita. Batteria: {battery}%")

        if battery < 30:
            print("ATTENZIONE: Batteria bassa. Aborto il volo.")
            return

        print("Avvio sequenza di volo...")
        tello2.takeoff()
        
        tello2.move_up(100)

        tello2.move_forward(250)
        
        tello2.flip_forward()
        get_battery_level(tello2, 13)

        tello2.move_forward(250)
        
        tello2.flip_forward()
        get_battery_level(tello2, 13)

        tello2.rotate_clockwise(180)
        
        tello2.flip_forward()
        get_battery_level(tello2, 13)

        tello2.move_forward(500)

        tello2.land()
        get_battery_level(tello2, 13)

        print("Sequenza di volo completata.")

    except Exception as e:
        print(f"ERRORE GRAVE nel volo: {e}")
        try:
            tello2.land()
        except:
            pass
    finally:
        print("Il drone ha terminato l'operazione.")

def vola3(): # Vola ElonMusk 14 e ElonMusk 13 insieme
    print("PIN selezionato: 2")
    thread_1 = threading.Thread(target=vola1)
    thread_2 = threading.Thread(target=vola2)
    thread_1.start()
    thread_2.start()
    print("Sto facendo volare i due droni")

def vola4(): # Test di emergenza: solo decollo e atterraggio
    print("PIN selezionato: 3")
    print("Tentativo di connessione al tello2...")
    
    try:
        tello2.connect()
        battery = tello2.get_battery()
        print(f"Connessione tello2 Riuscita. Batteria: {battery}%")

        if battery < 20:
            print("ATTENZIONE: Batteria bassa. Aborto il volo.")
            return

        print("Avvio sequenza di volo...")
        tello2.takeoff()
        tello2.move_up(100)
        tello2.land()
        get_battery_level(tello2, 13)

        print("Sequenza di volo completata.")

    except Exception as e:
        print(f"ERRORE GRAVE nel volo: {e}")
        try:
            tello2.land()
        except:
            pass
    finally:
        print("Il drone ha terminato l'operazione.")
    
# --- RICEVITORE HTTP (WEBHOOK LOCALE) ---

@app.route('/vola1', methods=['GET'])
def trigger_vola1():
    """Endpoint chiamato dall'Halocode quando il pulsante è premuto."""
    
    print("-" * 30)
    print("!!! RICEVUTO COMANDO 'VOLA1' DA HALOCODE !!!")
    print("-" * 30)

    # Avvia la sequenza di volo in un thread separato per non bloccare il server Flask
    tello1_thread = threading.Thread(target=vola1)
    tello1_thread.start()
    
    return jsonify({"status": "success", "message": "Comando di volo ricevuto e avviato in background"}), 200

@app.route('/vola2', methods=['GET'])
def trigger_vola2():
    """Endpoint chiamato dall'Halocode quando il pulsante è premuto."""
    
    print("-" * 30)
    print("!!! RICEVUTO COMANDO 'VOLA2' DA HALOCODE !!!")
    print("-" * 30)

    # Avvia la sequenza di volo in un thread separato per non bloccare il server Flask
    tello2_thread = threading.Thread(target=vola2)
    tello2_thread.start()
    
    return jsonify({"status": "success", "message": "Comando di volo ricevuto e avviato in background"}), 200

@app.route('/vola3', methods=['GET'])
def trigger_vola3():
    """Endpoint chiamato dall'Halocode quando il pulsante è premuto."""
    
    print("-" * 30)
    print("!!! RICEVUTO COMANDO 'VOLA3' DA HALOCODE !!!")
    print("-" * 30)

    # Avvia la sequenza di volo in un thread separato per non bloccare il server Flask
    tello_thread = threading.Thread(target=vola3)
    tello_thread.start()
    
    return jsonify({"status": "success", "message": "Comando di volo ricevuto e avviato in background"}), 200

@app.route('/vola4', methods=['GET'])
def trigger_vola4():
    """Endpoint chiamato dall'Halocode quando il pulsante è premuto."""
    
    print("-" * 30)
    print("!!! RICEVUTO COMANDO 'VOLA4' DA HALOCODE !!!")
    print("-" * 30)

    # Avvia la sequenza di volo in un thread separato per non bloccare il server Flask
    tello_thread = threading.Thread(target=vola4)
    tello_thread.start()
    
    return jsonify({"status": "success", "message": "Comando di volo ricevuto e avviato in background"}), 200

# Funzione principale per avviare il server

if __name__ == '__main__':
    print(f"--- SERVER TELLO ATTIVO ---")
    print(f"In ascolto su http://{PC_IP_ADDRESS}:{SERVER_PORT}/")
    print(f"URL di trigger per Halocode: http://{PC_IP_ADDRESS}:{SERVER_PORT}/vola1")
    print("Assicurati che Halocode e Tello siano sulla stessa rete Wi-Fi.")
    
    # IMPORTANTE: host='0.0.0.0' rende il server accessibile da tutti gli altri dispositivi sulla LAN (inclusa Halocode)
    app.run(host='0.0.0.0', port=SERVER_PORT)