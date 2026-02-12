package BattagliaNavale.Server;

import javax.swing.JFrame;

public class ServerFrame extends JFrame {
    public ServerFrame() {
        setTitle("Server");
        setSize(500, 500);
        setResizable(false);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLocationRelativeTo(null);
        add(new ServerPanelControl());
        setVisible(true);
    }
    
    public static void main(String[] args) {
        new ServerFrame();
    }
}
