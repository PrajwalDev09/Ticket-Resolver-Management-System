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

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.awt.FlowLayout;


public class CreateTicket extends JFrame implements ActionListener {
	
	JTextField clientid = null;
	JTextField description = null;
	Timestamp opentime;
	JTextField closetime = null;
	JTextField assigned = null;
	JComboBox status;
	JLabel label = new JLabel(" ");
	JComboBox sev;
//	JTextField txt;
	
	public CreateTicket(){
		
		setSize(300,300);
		setVisible(true);
		this.setLayout(new GridLayout(9,9));
		
		String[] sevStrings = { "urgent", "normal", "long therm" };
		
		String[] statusStrings = {"opened", "closed" };
		

		
		
		
		
		add(new JLabel("Insert Ticket"));
		add(new JLabel(" "));
		
		add(new JLabel("client id"));
		clientid = new JTextField(20);
		this.add(clientid);
		
		add(new JLabel("description"));
		description = new JTextField(20);
		this.add(description);
		
		add(new JLabel("sev"));
		sev = new JComboBox(sevStrings);
		this.add(sev);
		
		
//		add(new JLabel("opentime"));
//		opentime = new JTextField(20);
//		this.add(opentime);
		
		add(new JLabel("closetime"));
		closetime = new JTextField(20);
		this.add(closetime);
		
		add(new JLabel("assigned"));
		assigned = new JTextField(20);
		this.add(assigned);
		
		add(new JLabel("status"));
		status = new JComboBox(statusStrings);
		this.add(status);
		
		JButton insert = new JButton("Insert a ticket");
		
		insert.addActionListener(this);
		
		this.add(insert);
		
		
		
	}
	
	public void selectionPerformed(ActionEvent e) {
		// TODO Auto-generated method stub
	    String selected = sev.getSelectedItem().toString();
			
		int dash = selected.indexOf("-");
		String sev = selected.substring(0, dash);
			
	    String stslc = status.getSelectedItem().toString();
			
	    int sts = selected.indexOf("-");
	    String status  = selected.substring(0, sts);
		
		
		
	}
	

	public void CreateNewTicket(){
		
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
    		Calendar calendar = Calendar.getInstance();
    	    java.sql.Timestamp opentime = new java.sql.Timestamp(calendar.getTime().getTime());
    	    
    	    String cId = clientid.getText();
    	    String desc = description.getText(); 
    	    String sv = (String) sev.getSelectedItem();
    	    Timestamp ot = opentime;
    	    String ct = closetime.getText();
    	    String ass = assigned.getText();
    	    String st = (String) status.getSelectedItem();
    	    
    	    
    	    if (stmt.execute("INSERT INTO `test`.`tickets` (`clientid`, `description`, `sev`, `opentime`, `closetime`, `assigned`, `status` )"  + "VALUES('"+cId+"','"+desc+"' ,'"+sv+"' ,'"+ot+"' ,'"+ct+"' ,'"+ass+"' ,'"+st+"');")) {
    	    
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
		new CreateTicket();
		
	}
	
	
	@Override
	public void actionPerformed(ActionEvent e) {
		
		CreateNewTicket();
		
	
	}
	
}