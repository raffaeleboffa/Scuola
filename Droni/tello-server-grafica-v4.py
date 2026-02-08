import tkinter as tk
from tkinter import ttk, scrolledtext
from flask import Flask, jsonify
from werkzeug.serving import make_server
from djitellopy import Tello
import threading
import time
import json
import os
import sys

class TextRedirector(object):
    def __init__(self, widget, tag="stdout"):
        self.widget = widget
        self.tag = tag
    def write(self, str):
        self.widget.configure(state="normal")
        self.widget.insert("end", str, (self.tag,))
        self.widget.see("end")
        self.widget.configure(state="disabled")
    def flush(self): pass

class TelloServerApp:
    def __init__(self, root):
        self.root = root
        self.root.title("Tello Edu Control Panel - v4")
        self.root.geometry("1100x850")
        
        self.flask_app = Flask(__name__)
        self.server = None
        self.is_running = False

        # Configurazione Iniziale con Nomi Drone
        self.config = {
            "pc_ip": "10.18.254.10",
            "port": "5000",
            "tello1_ip": "10.18.254.249",
            "tello1_name": "Tello 14", # Nome personalizzabile
            "tello2_ip": "10.18.254.122",
            "tello2_name": "Tello 13", # Nome personalizzabile
            "script1": "decolla\nsu 100\natterra",
            "script2": "decolla\nsu 100\natterra",
            "script3_t1": "decolla\natterra",
            "script3_t2": "decolla\natterra",
            "script4": "decolla\natterra"
        }
        
        self.load_config()
        self.setup_ui()
        self.setup_routes()

    def setup_ui(self):
        self.tabs = ttk.Notebook(self.root)
        
        self.tab_config = ttk.Frame(self.tabs)
        self.tab_flight = ttk.Frame(self.tabs)
        self.tab_manual = ttk.Frame(self.tabs)  # Tab Controllo Manuale
        self.tab_help = ttk.Frame(self.tabs)
        self.tab_log = ttk.Frame(self.tabs)
        
        self.tabs.add(self.tab_config, text='1. Rete e Nomi')
        self.tabs.add(self.tab_flight, text='2. Percorsi di Volo')
        self.tabs.add(self.tab_manual, text='3. Controllo Manuale')
        self.tabs.add(self.tab_help, text='4. Legenda Comandi')
        self.tabs.add(self.tab_log, text='5. Console')
        self.tabs.pack(expand=1, fill="both")

        # --- TAB 1: RETE E NOMI ---
        f_net = ttk.LabelFrame(self.tab_config, text="Configurazione Dispositivi")
        f_net.pack(padx=20, pady=20, fill="x")
        
        # IP PC
        ttk.Label(f_net, text="IP PC (Server):").grid(row=0, column=0, padx=5, pady=5, sticky="e")
        self.ent_pc = ttk.Entry(f_net, width=20); self.ent_pc.insert(0, self.config["pc_ip"]); self.ent_pc.grid(row=0, column=1, sticky="w")
        
        # Drone 1
        ttk.Label(f_net, text="IP Drone 1:").grid(row=1, column=0, padx=5, pady=5, sticky="e")
        self.ent_t1_ip = ttk.Entry(f_net, width=20); self.ent_t1_ip.insert(0, self.config["tello1_ip"]); self.ent_t1_ip.grid(row=1, column=1)
        ttk.Label(f_net, text="Nome:").grid(row=1, column=2, padx=5, pady=5)
        self.ent_t1_name = ttk.Entry(f_net, width=15); self.ent_t1_name.insert(0, self.config["tello1_name"]); self.ent_t1_name.grid(row=1, column=3)
        
        # Drone 2
        ttk.Label(f_net, text="IP Drone 2:").grid(row=2, column=0, padx=5, pady=5, sticky="e")
        self.ent_t2_ip = ttk.Entry(f_net, width=20); self.ent_t2_ip.insert(0, self.config["tello2_ip"]); self.ent_t2_ip.grid(row=2, column=1)
        ttk.Label(f_net, text="Nome:").grid(row=2, column=2, padx=5, pady=5)
        self.ent_t2_name = ttk.Entry(f_net, width=15); self.ent_t2_name.insert(0, self.config["tello2_name"]); self.ent_t2_name.grid(row=2, column=3)

        self.btn_save = ttk.Button(self.tab_config, text="SALVA E AGGIORNA PANNELLI", command=self.save_config)
        self.btn_save.pack(pady=5)

        self.btn_start = ttk.Button(self.tab_config, text="AVVIA SERVER WEBHOOK", command=self.start_server)
        self.btn_start.pack(pady=10)

        self.btn_stop = ttk.Button(self.tab_config, text="ARRESTA SERVER", command=self.stop_server, state="disabled")
        self.btn_stop.pack(pady=5)

        # --- TAB 2: VOLO ---
        self.f_main_flight = ttk.Frame(self.tab_flight)
        self.f_main_flight.pack(fill="both", expand=True, padx=10, pady=10)
        self.render_flight_panels()

        # --- TAB 3: CONTROLLO MANUALE ---
        self.setup_manual_tab()

        # --- TAB 4 & 5 (Legenda e Log) ---
        help_text = "COMANDI: decolla, atterra, su X, giu X, avanti X, indietro X, destra X, sinistra X, ruota_orario X, flip_avanti, aspetta X"
        tk.Label(self.tab_help, text=help_text, justify="left", font=("Consolas", 11), padx=20, pady=20).pack()
        
        self.txt_log = scrolledtext.ScrolledText(self.tab_log, state='disabled', bg='black', fg='#00FF00')
        self.txt_log.pack(fill="both", expand=True)
        sys.stdout = TextRedirector(self.txt_log)

    def render_flight_panels(self):
        """Crea o aggiorna i pannelli di volo con i nomi correnti dei droni"""
        for widget in self.f_main_flight.winfo_children():
            widget.destroy()

        # VOLA 1
        f1 = ttk.LabelFrame(self.f_main_flight, text=f"VOLA 1 - {self.ent_t1_name.get()}")
        f1.grid(row=0, column=0, sticky="nsew", padx=5, pady=5)
        self.txt_s1 = scrolledtext.ScrolledText(f1, width=30, height=10); self.txt_s1.insert("1.0", self.config["script1"]); self.txt_s1.pack(fill="both", expand=True)
        ttk.Button(f1, text="Start Volo 1", command=self.start_flight_1).pack(pady=2, fill="x")

        # VOLA 2
        f2 = ttk.LabelFrame(self.f_main_flight, text=f"VOLA 2 - {self.ent_t2_name.get()}")
        f2.grid(row=0, column=1, sticky="nsew", padx=5, pady=5)
        self.txt_s2 = scrolledtext.ScrolledText(f2, width=30, height=10); self.txt_s2.insert("1.0", self.config["script2"]); self.txt_s2.pack(fill="both", expand=True)
        ttk.Button(f2, text="Start Volo 2", command=self.start_flight_2).pack(pady=2, fill="x")

        # VOLA 3
        f3 = ttk.LabelFrame(self.f_main_flight, text="VOLA 3 - SINCRONIZZATO")
        f3.grid(row=1, column=0, sticky="nsew", padx=5, pady=5)
        ttk.Label(f3, text=f"Percorso {self.ent_t1_name.get()}:").pack()
        self.txt_s3_t1 = scrolledtext.ScrolledText(f3, height=5); self.txt_s3_t1.insert("1.0", self.config["script3_t1"]); self.txt_s3_t1.pack(fill="both", expand=True)
        ttk.Label(f3, text=f"Percorso {self.ent_t2_name.get()}:").pack()
        self.txt_s3_t2 = scrolledtext.ScrolledText(f3, height=5); self.txt_s3_t2.insert("1.0", self.config["script3_t2"]); self.txt_s3_t2.pack(fill="both", expand=True)
        ttk.Button(f3, text="Start Volo 3 (Sync)", command=self.start_flight_3).pack(pady=2, fill="x")

        # VOLA 4
        f4 = ttk.LabelFrame(self.f_main_flight, text=f"VOLA 4 - EMERGENZA ({self.ent_t1_name.get()} e {self.ent_t2_name.get()})")
        f4.grid(row=1, column=1, sticky="nsew", padx=5, pady=5)
        self.txt_s4 = scrolledtext.ScrolledText(f4, width=30, height=10); self.txt_s4.insert("1.0", self.config["script4"]); self.txt_s4.pack(fill="both", expand=True)
        ttk.Button(f4, text="Start Volo 4", command=self.start_flight_4).pack(pady=2, fill="x")

        self.f_main_flight.columnconfigure(0, weight=1); self.f_main_flight.columnconfigure(1, weight=1)
        self.f_main_flight.rowconfigure(0, weight=1); self.f_main_flight.rowconfigure(1, weight=1)

    def setup_manual_tab(self):
        # Frame Selezione Drone
        f_sel = ttk.LabelFrame(self.tab_manual, text="Selezione Drone")
        f_sel.pack(fill="x", padx=10, pady=5)

        self.manual_drone_var = tk.StringVar(value="1")
        ttk.Radiobutton(f_sel, text="Drone 1", variable=self.manual_drone_var, value="1").pack(side="left", padx=10)
        ttk.Radiobutton(f_sel, text="Drone 2", variable=self.manual_drone_var, value="2").pack(side="left", padx=10)

        self.btn_man_connect = ttk.Button(f_sel, text="CONNETTI", command=self.manual_connect)
        self.btn_man_connect.pack(side="left", padx=10)

        self.lbl_battery = ttk.Label(f_sel, text="Batteria: --%", font=("Arial", 12, "bold"))
        self.lbl_battery.pack(side="left", padx=20)

        # Frame Comandi
        f_ctrl = ttk.Frame(self.tab_manual)
        f_ctrl.pack(fill="both", expand=True, padx=10, pady=5)

        # Step Input
        f_param = ttk.Frame(f_ctrl)
        f_param.pack(pady=5)
        ttk.Label(f_param, text="Step (cm/gradi):").pack(side="left")
        self.ent_step = ttk.Entry(f_param, width=5)
        self.ent_step.insert(0, "30")
        self.ent_step.pack(side="left", padx=5)

        # Griglia Pulsanti
        f_grid = ttk.LabelFrame(f_ctrl, text="Pannello Comandi")
        f_grid.pack(pady=10, padx=10)

        # Riga 0
        ttk.Button(f_grid, text="Decolla", command=lambda: self.run_manual_cmd("takeoff"), width=15).grid(row=0, column=0, padx=5, pady=5)
        ttk.Button(f_grid, text="Atterra", command=lambda: self.run_manual_cmd("land"), width=15).grid(row=0, column=2, padx=5, pady=5)

        # Riga 1 (Movimento Alto/Basso)
        ttk.Button(f_grid, text="Su", command=lambda: self.run_manual_cmd("up")).grid(row=1, column=1, padx=5, pady=5)

        # Riga 2 (Movimento Orizzontale)
        ttk.Button(f_grid, text="Sinistra", command=lambda: self.run_manual_cmd("left")).grid(row=2, column=0, padx=5, pady=5)
        ttk.Button(f_grid, text="Avanti", command=lambda: self.run_manual_cmd("forward")).grid(row=2, column=1, padx=5, pady=5)
        ttk.Button(f_grid, text="Destra", command=lambda: self.run_manual_cmd("right")).grid(row=2, column=2, padx=5, pady=5)

        # Riga 3
        ttk.Button(f_grid, text="Giu", command=lambda: self.run_manual_cmd("down")).grid(row=3, column=1, padx=5, pady=5)

        # Riga 4 (Rotazione e Indietro)
        ttk.Button(f_grid, text="Ruota SX", command=lambda: self.run_manual_cmd("ccw")).grid(row=4, column=0, padx=5, pady=5)
        ttk.Button(f_grid, text="Indietro", command=lambda: self.run_manual_cmd("back")).grid(row=4, column=1, padx=5, pady=5)
        ttk.Button(f_grid, text="Ruota DX", command=lambda: self.run_manual_cmd("cw")).grid(row=4, column=2, padx=5, pady=5)
        
        # Riga 5 - Flip
        ttk.Button(f_grid, text="Flip Avanti", command=lambda: self.run_manual_cmd("flip_forward")).grid(row=5, column=1, padx=5, pady=5)

    def manual_connect(self):
        drone_choice = self.manual_drone_var.get()
        if drone_choice == "1":
            ip = self.ent_t1_ip.get()
            name = self.ent_t1_name.get()
        else:
            ip = self.ent_t2_ip.get()
            name = self.ent_t2_name.get()
            
        def _conn():
            try:
                print(f"[{name}] Connessione manuale in corso a {ip}...")
                self.manual_drone = Tello(host=ip)
                self.manual_drone.connect()
                bat = self.manual_drone.get_battery()
                
                def update_lbl():
                    self.lbl_battery.config(text=f"Batteria: {bat}%", foreground="green" if bat > 20 else "red")
                    
                self.root.after(0, update_lbl)
                print(f"[{name}] Connesso. Batteria: {bat}%")
            except Exception as e:
                print(f"[{name}] Errore connessione manuale: {e}")
                self.manual_drone = None
        
        threading.Thread(target=_conn).start()

    def run_manual_cmd(self, cmd):
        if not hasattr(self, 'manual_drone') or self.manual_drone is None:
            print("ERRORE: Nessun drone connesso in manuale. Premi CONNETTI.")
            return

        try:
            val = int(self.ent_step.get())
        except:
            val = 30
        
        def _exec():
            try:
                print(f"[MANUAL] Invio comando: {cmd} (val={val})")
                if cmd == "takeoff": self.manual_drone.takeoff()
                elif cmd == "land": self.manual_drone.land()
                elif cmd == "up": self.manual_drone.move_up(val)
                elif cmd == "down": self.manual_drone.move_down(val)
                elif cmd == "left": self.manual_drone.move_left(val)
                elif cmd == "right": self.manual_drone.move_right(val)
                elif cmd == "forward": self.manual_drone.move_forward(val)
                elif cmd == "back": self.manual_drone.move_back(val)
                elif cmd == "cw": self.manual_drone.rotate_clockwise(val)
                elif cmd == "ccw": self.manual_drone.rotate_counter_clockwise(val)
                elif cmd == "flip_forward": self.manual_drone.flip_forward()
                
                # Aggiorna batteria dopo il comando
                bat = self.manual_drone.get_battery()
                self.root.after(0, lambda: self.lbl_battery.config(text=f"Batteria: {bat}%", foreground="green" if bat > 20 else "red"))
                print(f"[MANUAL] Comando eseguito. Batt: {bat}%")
            except Exception as e:
                print(f"[MANUAL] Errore esecuzione {cmd}: {e}")

        threading.Thread(target=_exec).start()

    def parse_and_fly(self, ip, script, name):
        try:
            drone = Tello(host=ip)
            drone.connect()
            print(f"[{name}] Connesso! Batteria: {drone.get_battery()}%")
            for line in script.split('\n'):
                p = line.strip().lower().split()
                if not p: continue
                cmd = p[0]; val = int(p[1]) if len(p) > 1 else 0
                print(f"[{name}] Eseguo: {cmd} {val if val>0 else ''}")
                if cmd == "decolla": drone.takeoff()
                elif cmd == "atterra": drone.land()
                elif cmd == "su": drone.move_up(val)
                elif cmd == "giu": drone.move_down(val)
                elif cmd == "avanti": drone.move_forward(val)
                elif cmd == "indietro": drone.move_back(val)
                elif cmd == "destra": drone.move_right(val)
                elif cmd == "sinistra": drone.move_left(val)
                elif cmd == "ruota_orario": drone.rotate_clockwise(val)
                elif cmd == "flip_avanti": drone.flip_forward()
                elif cmd == "aspetta": time.sleep(val)
            drone.land()
        except Exception as e: print(f"ERRORE {name} ({ip}): {e}")

    def load_config(self):
        if os.path.exists("config_v4.json"):
            with open("config_v4.json", "r") as f: self.config.update(json.load(f))

    def save_config(self):
        self.config.update({
            "pc_ip": self.ent_pc.get(),
            "tello1_ip": self.ent_t1_ip.get(), "tello1_name": self.ent_t1_name.get(),
            "tello2_ip": self.ent_t2_ip.get(), "tello2_name": self.ent_t2_name.get(),
            "script1": self.txt_s1.get("1.0", "end-1c"), "script2": self.txt_s2.get("1.0", "end-1c"),
            "script3_t1": self.txt_s3_t1.get("1.0", "end-1c"), "script3_t2": self.txt_s3_t2.get("1.0", "end-1c"),
            "script4": self.txt_s4.get("1.0", "end-1c")
        })
        with open("config_v4.json", "w") as f: json.dump(self.config, f)
        self.render_flight_panels() # Aggiorna le etichette con i nuovi nomi
        print("Configurazione salvata e nomi aggiornati.")

    def start_flight_1(self):
        threading.Thread(target=self.parse_and_fly, args=(self.ent_t1_ip.get(), self.txt_s1.get("1.0", "end"), self.ent_t1_name.get())).start()

    def start_flight_2(self):
        threading.Thread(target=self.parse_and_fly, args=(self.ent_t2_ip.get(), self.txt_s2.get("1.0", "end"), self.ent_t2_name.get())).start()

    def start_flight_3(self):
        threading.Thread(target=self.parse_and_fly, args=(self.ent_t1_ip.get(), self.txt_s3_t1.get("1.0", "end"), f"{self.ent_t1_name.get()} (V3)")).start()
        threading.Thread(target=self.parse_and_fly, args=(self.ent_t2_ip.get(), self.txt_s3_t2.get("1.0", "end"), f"{self.ent_t2_name.get()} (V3)")).start()

    def start_flight_4(self):
        try:
            threading.Thread(target=self.parse_and_fly, args=(self.ent_t1_ip.get(), self.txt_s4.get("1.0", "end"), f"{self.ent_t1_name.get()} (EMG)")).start()
        except:
            print("Errore nell'avvio del volo di emergenza per il Drone 1.")
        try:
            threading.Thread(target=self.parse_and_fly, args=(self.ent_t2_ip.get(), self.txt_s4.get("1.0", "end"), f"{self.ent_t2_name.get()} (EMG)")).start()
        except:
            print("Errore nell'avvio del volo di emergenza per il Drone 2.")

    def setup_routes(self):
        @self.flask_app.route('/vola1')
        def v1():
            self.start_flight_1()
            return jsonify({"status": "ok"})
        @self.flask_app.route('/vola2')
        def v2():
            self.start_flight_2()
            return jsonify({"status": "ok"})
        @self.flask_app.route('/vola3')
        def v3():
            self.start_flight_3()
            return jsonify({"status": "ok"})
        @self.flask_app.route('/vola4')
        def v4():
            self.start_flight_4()
            return jsonify({"status": "ok"})

    def check_batteries(self):
        """Controlla la batteria di entrambi i droni in un thread separato"""
        def _task():
            drones = [
                (self.ent_t1_ip.get(), self.ent_t1_name.get()),
                (self.ent_t2_ip.get(), self.ent_t2_name.get())
            ]
            for ip, name in drones:
                if ip and ip.strip():
                    try:
                        print(f"[{name}] Tentativo di connessione per controllo batteria...")
                        d = Tello(host=ip)
                        d.connect()
                        batt = d.get_battery()
                        print(f"[{name}] Batteria rilevata: {batt}%")
                    except Exception as e:
                        print(f"[{name}] Errore connessione batteria ({ip}): {e}")
        threading.Thread(target=_task, daemon=True).start()

    def start_server(self):
        if not self.is_running:
            self.save_config()
            try:
                self.server = make_server('0.0.0.0', 5000, self.flask_app)
                threading.Thread(target=self.server.serve_forever, daemon=True).start()
                self.is_running = True
                
                self.btn_start.config(state="disabled")
                self.btn_stop.config(state="normal")
                
                # Blocca campi config
                self.ent_pc.config(state="readonly")
                self.ent_t1_ip.config(state="readonly"); self.ent_t1_name.config(state="readonly")
                self.ent_t2_ip.config(state="readonly"); self.ent_t2_name.config(state="readonly")
                
                print(f"SERVER ATTIVO su {self.ent_pc.get()}:5000")
                self.check_batteries()
            except Exception as e:
                print(f"ERRORE AVVIO SERVER: {e}")

    def stop_server(self):
        if self.is_running and self.server:
            self.server.shutdown()
            self.server = None
            self.is_running = False
            
            self.btn_start.config(state="normal")
            self.btn_stop.config(state="disabled")
            
            # Sblocca campi
            self.ent_pc.config(state="normal")
            self.ent_t1_ip.config(state="normal"); self.ent_t1_name.config(state="normal")
            self.ent_t2_ip.config(state="normal"); self.ent_t2_name.config(state="normal")

            print("SERVER ARRESTATO. Configurazione modificabile.")

if __name__ == "__main__":
    root = tk.Tk(); app = TelloServerApp(root); root.mainloop()