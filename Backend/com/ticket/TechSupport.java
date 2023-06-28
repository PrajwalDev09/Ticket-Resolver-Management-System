import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.JTextField;

import java.awt.GridLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import java.sql.ResultSet;

public class TechSupport extends JFrame implements ActionListener {
    public TechSupport(){
    	
    	
    	setSize(300,300);
		setVisible(true);
		this.setLayout(new GridLayout(3,1));
    
    	JButton vTickets = new JButton("View Tickets");
		vTickets.addActionListener(this);
		// we are adding on a label to the button here
		// so later we will know which button has been clicked.
		vTickets.setActionCommand("View Tickets");
		this.add(vTickets);
		
		JButton insTicket = new JButton("Insert Ticket");
		insTicket.addActionListener(this);
		insTicket.setActionCommand("Insert Ticket");
		this.add(insTicket);
		
    	
    	
    	
    }
	public static void main(String[] args) {
		// TODO Auto-generated method stub
			new TechSupport();
	}
	@Override
	public void actionPerformed(ActionEvent e) {
		if(e.getActionCommand().equals("View Tickets")){
			
			new ViewTicket();
		}
		else if(e.getActionCommand().equals("Insert Ticket")){
			new CreateTicket();
			
		}
		
	}

}
