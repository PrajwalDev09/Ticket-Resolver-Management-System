import java.awt.GridLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import java.util.Calendar;

import javax.swing.JButton;
import javax.swing.JComboBox;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JTextField;

import java.sql.ResultSet;
import java.sql.Timestamp;
import java.sql.*;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.awt.FlowLayout;


public class ChangePass extends JFrame implements ActionListener {
	
	JTextField username = null;
	JTextField password = null;
	JLabel label = new JLabel(" ");
	

	
	public ChangePass(){
		
		setSize(300,300);
		setVisible(true);
		this.setLayout(new GridLayout(3,3));
		
		add(new JLabel("Select a User"));
		username = new JTextField(20);
		this.add(username);
		
		add(new JLabel("New Password"));
		password = new JTextField(20);
		this.add(password);
		
		JButton np = new JButton("Change Password");
		np.addActionListener(this);
		this.add(np);
		
		
		
	}
		
		
		public void NewPass() {
		
		try {
			
			  Class.forName("com.mysql.jdbc.Driver").newInstance();
			
			}catch(Exception e ){}
			
			
			Connection conn = null;
	    	Statement stmt = null;
	    	ResultSet rs = null;
	    	try {
	    	    conn =
	    	       DriverManager.getConnection("jdbc:mysql://127.0.0.1/test?user=root&password=");

	    	    // Do something with the Connection
	    	    stmt = conn.createStatement();

	    	    // or alternatively, if you don't know ahead of time that
	    	    // the query will be a SELECT...
	    	    String un = username.getText();
	    	    String pw = password.getText();

	    	    
	    	        if (stmt.execute("UPDATE systemusers SET password = '"+pw+"' where username = '"+un+"' ")) {
	    	        
	    	    }
		
		 		} catch (SQLException ex) {
	    	    // handle any errors
	    	    System.out.println("SQLException: " + ex.getMessage());
	    	    System.out.println("SQLState: " + ex.getSQLState());
	    	    System.out.println("VendorError: " + ex.getErrorCode());
	    	    
		 	}
		
	    	
		
		
}

	public static void main(String[] args) {
	// TODO Auto-generated method stub
		new ChangePass();
		
	}
	
	
	@Override
	public void actionPerformed(ActionEvent e) {
		
		NewPass();
		
	
	}
	
}