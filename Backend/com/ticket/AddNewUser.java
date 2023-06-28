import java.awt.GridLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JTextField;

import java.sql.ResultSet;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.Timestamp;
import java.util.Calendar;


public class AddNewUser extends JFrame implements ActionListener {
	
	JTextField username;
	JTextField password;
	JTextField type;
	
	public AddNewUser(){
		
	
		
		
		setSize(300,300);
		setVisible(true);
		this.setLayout(new GridLayout(4,4));
		
		add(new JLabel("username"));
		username = new JTextField(20);
		this.add(username);
		
		add(new JLabel("password"));
		password = new JTextField(20);
		this.add(password);
		
		add(new JLabel("type"));
		type = new JTextField(20);
		this.add(type);
		
		JButton nu = new JButton("Add new user");
		nu.addActionListener(this);
		// we are adding on a label to the button here
		// so later we will know which button has been clicked.
		nu.setActionCommand("Add new user");
		this.add(nu);
		

		
		

		
		
	
	}
	public static void main(String[] args) {
		// TODO Auto-generated method stub
		new AddNewUser();
	}
	
	public void NewUser(){
		
		try {
			
			  Class.forName("com.mysql.jdbc.Driver").newInstance();
			
			}catch(Exception e ){}
		
			Connection conn = null;
			Statement stmt = null;
			ResultSet rs = null;
		
		
		try {
    	    conn =
    	       DriverManager.getConnection("jdbc:mysql://127.0.0.1/test?user=root&password=");

    	    stmt = conn.createStatement();
    		
    	    String un = username.getText();
    	    String pass = password.getText(); 
    	    String tp = type.getText(); 
    	    
    	    
    	    
    	    if (stmt.execute("INSERT INTO `test`.`systemusers` (`username`, `password`, `type`)"  + "VALUES('"+un+"','"+pass+"' ,'"+tp+"');")) {
    	    
		}

	 	} catch (SQLException ex) {
    	    // handle any errors
    	    System.out.println("SQLException: " + ex.getMessage());
    	    System.out.println("SQLState: " + ex.getSQLState());
    	    System.out.println("VendorError: " + ex.getErrorCode());
	 	}
    	    
	}
	
	@Override
	public void actionPerformed(ActionEvent e) {
		
		// if you want to set a label for each of the buttons
		// and then redirect the user to a different part of the program
		// you can use the getActionCommand to check which button
		// has sent the request
		if(e.getActionCommand().equals("Add new user")){
			
			NewUser();
		}
		else if(e.getActionCommand().equals("login")){
			
		}
		
		
		
	
		
	}

}
