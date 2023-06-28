import java.awt.GridLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JOptionPane;
import javax.swing.JTextField;

import java.sql.ResultSet;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;


public class Admin extends JFrame implements ActionListener {
	JButton password;
	JButton user;
	
	public Admin(){
		
	
		
		
		setSize(300,300);
		setVisible(true);
		this.setLayout(new GridLayout(3,3));
		

		
		JButton user = new JButton("New User");
		user.addActionListener(this);
		// we are adding on a label to the button here
		// so later we will know which button has been clicked.
		user.setActionCommand("user");
		this.add(user);
		
		JButton password = new JButton("Change Password");
		password.addActionListener(this);
		// we are adding on a label to the button here
		// so later we will know which button has been clicked.
		password.setActionCommand("password");
		this.add(password);
			
	
	}
	public static void main(String[] args) {
		// TODO Auto-generated method stub
		new Admin();
	}

	
	@Override
	public void actionPerformed(ActionEvent e) {
		if(e.getActionCommand().equals("user")){
			
			new AddNewUser();
		}
		else if(e.getActionCommand().equals("password")){
			new ChangePass();
			
		}
		
	}	
}		
	
		



