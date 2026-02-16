package BattagliaNavale.Client;

import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JTextField;

public class ClientPanelControl extends JPanel {
    private JPanel setting;

    private ClientManager clientManager = new ClientManager();

    public ClientPanelControl() {
        setLayout(null);

        setting = new JPanel();
        setting.setLayout(null);
        setting.setBounds(0, 0, 500, 500);

        JLabel lbl_richiestaIP = new JLabel("Inserisci l'indirizzo IP del server:");
        lbl_richiestaIP.setBounds(10,10, 300, 30);
        setting.add(lbl_richiestaIP);

        JTextField txtField_IP = new JTextField();
        txtField_IP.setBounds(10, 40, 200, 30);
        setting.add(txtField_IP);

        JLabel lbl_richiestaPorta = new JLabel("Inserisci la porta del server:");
        lbl_richiestaPorta.setBounds(10,80, 300, 30);
        setting.add(lbl_richiestaPorta);

        JTextField txtField_Porta = new JTextField();
        txtField_Porta.setText("50900");
        txtField_Porta.setBounds(10, 110, 200, 30);
        setting.add(txtField_Porta);

        JButton btn_connetti = new JButton("Connetti");
        btn_connetti.addActionListener(e -> {
            String ip = txtField_IP.getText();
            int porta = Integer.parseInt(txtField_Porta.getText());
            clientManager.connetti(ip, porta);
        });
        btn_connetti.setBounds(10, 150, 80, 30);
        setting.add(btn_connetti);

        add(setting);
    }
}
