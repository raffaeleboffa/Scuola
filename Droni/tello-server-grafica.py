import tkinter as tk
from tkinter import ttk, scrolledtext, messagebox
from flask import Flask, jsonify
from djitellopy import Tello
import threading
import time
import json
import os
import sys

# --- CLASSE PER REDIRIGERE I PRINT SULLA GUI ---
class TextRedirector(object):
    def __init__(self, widget, tag="stdout"):
        self.widget = widget
        self.tag = tag

    def write(self, str):
        self.widget.configure(state="normal")
        self.widget.insert("end", str, (self.tag,))
        self.widget.see("end")
        self.widget.configure(state="disabled")
    
    def flush(self):
        pass

# --- APPLICAZIONE PRINCIPALE ---
class TelloServerApp:
    def __init__(self, root):
        self.root = root
        self.root.title("Tello Server Control Station")
        self.root.geometry("900x700")
        
        self.server_thread = None
        self.flask_app = Flask(__name__)
        self.is_running = False
        
        # Droni
        self.tello1 = None
        self.tello2 = None

        # Configurazione di Default
        self.config = {
            "pc_ip": "10.18.254.10",
            "port": "5000",
            "tello1_ip": "10.18.254.249", # ElonMusk 14
            "tello2_ip": "10.18.254.122", # ElonMusk 13
            "script1": "decolla\nsu 100\navanti 100\nflip\nindietro 100\natterra",
            "script2": "decolla\nsu 100\navanti 100\nflip\nindietro 100\natterra",
            "script4": "decolla\nsu 50\natterra"
        }
        
        self.load_config()
        self.setup_ui()
        self.setup_routes()

    def setup_ui(self):
        # --- TAB CONTROLLER ---
        tab_control = ttk.Notebook(self.root)
        
        self.tab_config = ttk.Frame(tab_control)
        self.tab_flight = ttk.Frame(tab_control)
        self.tab_log = ttk.Frame(tab_control)
        
        tab_control.add(self.tab_config, text='Configurazione Rete')
        tab_control.add(self.tab_flight, text='Pianificazione Volo')
        tab_control.add(self.tab_log, text='Log Console')
        tab_control.pack(expand=1, fill="both")

        # --- TAB 1: CONFIGURAZIONE ---
        frame_net = ttk.LabelFrame(self.tab_config, text="Impostazioni IP")
        frame_net.pack(padx=10, pady=10, fill="x")

        ttk.Label(frame_net, text="IP PC (Server):").grid(row=0, column=0, padx=5, pady=5)
        self.entry_pc_ip = ttk.Entry(frame_net)
        self.entry_pc_ip.insert(0, self.config["pc_ip"])
        self.entry_pc_ip.grid(row=0, column=1, padx=5, pady=5)

        ttk.Label(frame_net, text="Porta:").grid(row=0, column=2, padx=5, pady=5)
        self.entry_port = ttk.Entry(frame_net, width=10)
        self.entry_port.insert(0, self.config["port"])
        self.entry_port.grid(row=0, column=3, padx=5, pady=5)

        ttk.Label(frame_net, text="IP Tello 1 (Elon 14):").grid(row=1, column=0, padx=5, pady=5)
        self.entry_t1_ip = ttk.Entry(frame_net)
        self.entry_t1_ip.insert(0, self.config["tello1_ip"])
        self.entry_t1_ip.grid(row=1, column=1, padx=5, pady=5)

        ttk.Label(frame_net, text="IP Tello 2 (Elon 13):").grid(row=2, column=0, padx=5, pady=5)
        self.entry_t2_ip = ttk.Entry(frame_net)
        self.entry_t2_ip.insert(0, self.config["tello2_ip"])
        self.entry_t2_ip.grid(row=2, column=1, padx=5, pady=5)

        # Pulsanti Controllo Server
        frame_ctrl = ttk.Frame(self.tab_config)
        frame_ctrl.pack(pady=20)
        
        self.btn_start = ttk.Button(frame_ctrl, text="AVVIA SERVER", command=self.start_server)
        self.btn_start.pack(side="left", padx=10)
        
        self.lbl_status = ttk.Label(frame_ctrl, text="Server: FERMO", foreground="red", font=("Arial", 12, "bold"))
        self.lbl_status.pack(side="left", padx=10)

        self.btn_save = ttk.Button(frame_ctrl, text="Salva Configurazione", command=self.save_config)
        self.btn_save.pack(side="left", padx=10)

        # --- TAB 2: PIANIFICAZIONE VOLO ---
        # Script 1
        frame_s1 = ttk.LabelFrame(self.tab_flight, text="Percorso Tello 1 (Vola1 & Vola3)")
        frame_s1.pack(side="left", fill="both", expand=True, padx=5, pady=5)
        self.txt_script1 = scrolledtext.ScrolledText(frame_s1, width=30, height=20)
        self.txt_script1.insert("1.0", self.config["script1"])
        self.txt_script1.pack(fill="both", expand=True)

        # Script 2
        frame_s2 = ttk.LabelFrame(self.tab_flight, text="Percorso Tello 2 (Vola2 & Vola3)")
        frame_s2.pack(side="left", fill="both", expand=True, padx=5, pady=5)
        self.txt_script2 = scrolledtext.ScrolledText(frame_s2, width=30, height=20)
        self.txt_script2.insert("1.0", self.config["script2"])
        self.txt_script2.pack(fill="both", expand=True)

        # Script Emergenza
        frame_s4 = ttk.LabelFrame(self.tab_flight, text="Emergenza (Vola4)")
        frame_s4.pack(side="left", fill="both", expand=True, padx=5, pady=5)
        self.txt_script4 = scrolledtext.ScrolledText(frame_s4, width=30, height=20)
        self.txt_script4.insert("1.0", self.config["script4"])
        self.txt_script4.pack(fill="both", expand=True)
        
        lbl_help = ttk.Label(self.tab_flight, text="Comandi: decolla, atterra, su X, giu X, avanti X, indietro X, destra X, sinistra X, ruota X, flip")
        lbl_help.pack(side="bottom", pady=5)

        # --- TAB 3: LOG ---
        self.txt_log = scrolledtext.ScrolledText(self.tab_log, state='disabled', bg='black', fg='green')
        self.txt_log.pack(fill="both", expand=True)

        # Redirect stdout
        sys.stdout = TextRedirector(self.txt_log)
        sys.stderr = TextRedirector(self.txt_log)

    def load_config(self):
        if os.path.exists("config.json"):
            try:
                with open("config.json", "r") as f:
                    saved_config = json.load(f)
                    self.config.update(saved_config)
            except:
                print("Errore caricamento config.")

    def save_config(self):
        self.config["pc_ip"] = self.entry_pc_ip.get()
        self.config["port"] = self.entry_port.get()
        self.config["tello1_ip"] = self.entry_t1_ip.get()
        self.config["tello2_ip"] = self.entry_t2_ip.get()
        self.config["script1"] = self.txt_script1.get("1.0", "end-1c")
        self.config["script2"] = self.txt_script2.get("1.0", "end-1c")
        self.config["script4"] = self.txt_script4.get("1.0", "end-1c")
        
        with open("config.json", "w") as f:
            json.dump(self.config, f)
        print("Configurazione salvata!")

    def parse_and_fly(self, tello_obj, script_text, drone_name):
        """Interpreta i comandi di testo ed esegue le azioni sul drone"""
        if not tello_obj:
            print(f"{drone_name} non inizializzato.")
            return

        try:
            print(f"--- Connessione a {drone_name}... ---")
            tello_obj.connect()
            bat = tello_obj.get_battery()
            print(f"Connesso a {drone_name}. Batteria: {bat}%")
            if bat < 20:
                print(f"BATTERIA BASSA {drone_name}! ABORTO.")
                return
        except Exception as e:
            print(f"Errore connessione {drone_name}: {e}")
            return

        lines = script_text.split('\n')
        print(f"Avvio sequenza per {drone_name}...")
        
        try:
            for line in lines:
                parts = line.strip().lower().split()
                if not parts: continue
                
                cmd = parts[0]
                val = int(parts[1]) if len(parts) > 1 else 0
                
                print(f"Eseguo su {drone_name}: {cmd} {val if val else ''}")
                
                if cmd == "decolla": tello_obj.takeoff()
                elif cmd == "atterra": tello_obj.land()
                elif cmd == "su": tello_obj.move_up(val)
                elif cmd == "giu": tello_obj.move_down(val)
                elif cmd == "avanti": tello_obj.move_forward(val)
                elif cmd == "indietro": tello_obj.move_back(val)
                elif cmd == "destra": tello_obj.move_right(val)
                elif cmd == "sinistra": tello_obj.move_left(val)
                elif cmd == "ruota": tello_obj.rotate_clockwise(val)
                elif cmd == "flip": tello_obj.flip_forward()
                elif cmd == "wait": time.sleep(val)
                
            print(f"Sequenza {drone_name} completata.")
        except Exception as e:
            print(f"ERRORE VOLO {drone_name}: {e}")
            try: tello_obj.land()
            except: pass

    # --- FLIGHT WRAPPERS ---
    def run_vola1(self):
        # Inizializza al volo per prendere IP aggiornato
        t1 = Tello(host=self.entry_t1_ip.get())
        script = self.txt_script1.get("1.0", "end-1c")
        self.parse_and_fly(t1, script, "Tello 1")

    def run_vola2(self):
        t2 = Tello(host=self.entry_t2_ip.get())
        script = self.txt_script2.get("1.0", "end-1c")
        self.parse_and_fly(t2, script, "Tello 2")
        
    def run_vola3(self):
        # Vola entrambi insieme
        t1 = threading.Thread(target=self.run_vola1)
        t2 = threading.Thread(target=self.run_vola2)
        t1.start()
        t2.start()
        
    def run_vola4(self):
        # Script Emergenza (usa Tello 2 di default come nel vecchio script, o puoi cambiarlo)
        t2 = Tello(host=self.entry_t2_ip.get())
        script = self.txt_script4.get("1.0", "end-1c")
        self.parse_and_fly(t2, script, "Tello 2 (Emergenza)")

    # --- FLASK ROUTES ---
    def setup_routes(self):
        @self.flask_app.route('/vola1', methods=['GET'])
        def trigger_vola1():
            print("!!! TRIGGER VOLA 1 RICEVUTO !!!")
            threading.Thread(target=self.run_vola1).start()
            return jsonify({"status": "ok"}), 200

        @self.flask_app.route('/vola2', methods=['GET'])
        def trigger_vola2():
            print("!!! TRIGGER VOLA 2 RICEVUTO !!!")
            threading.Thread(target=self.run_vola2).start()
            return jsonify({"status": "ok"}), 200

        @self.flask_app.route('/vola3', methods=['GET'])
        def trigger_vola3():
            print("!!! TRIGGER VOLA 3 (DOPPIO) RICEVUTO !!!")
            threading.Thread(target=self.run_vola3).start()
            return jsonify({"status": "ok"}), 200
            
        @self.flask_app.route('/vola4', methods=['GET'])
        def trigger_vola4():
            print("!!! TRIGGER VOLA 4 RICEVUTO !!!")
            threading.Thread(target=self.run_vola4).start()
            return jsonify({"status": "ok"}), 200

    def run_flask(self):
        try:
            # host='0.0.0.0' è fondamentale per essere visti dall'Halocode
            port = int(self.entry_port.get())
            self.flask_app.run(host='0.0.0.0', port=port, use_reloader=False)
        except Exception as e:
            print(f"Errore Flask: {e}")

    def start_server(self):
        if self.is_running:
            return
        
        self.save_config() # Salva prima di partire
        self.server_thread = threading.Thread(target=self.run_flask, daemon=True)
        self.server_thread.start()
        self.is_running = True
        
        self.lbl_status.config(text="Server: ATTIVO", foreground="green")
        self.btn_start.config(state="disabled")
        
        print("-" * 30)
        print(f"SERVER AVVIATO SU PORTA {self.entry_port.get()}")
        print(f"Assicurati che Halocode punti a: {self.entry_pc_ip.get()}")
        print("-" * 30)

if __name__ == "__main__":
    root = tk.Tk()
    app = TelloServerApp(root)
    root.mainloop()