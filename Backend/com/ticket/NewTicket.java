import java.awt.FlowLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import javax.swing.JButton;
import javax.swing.JComboBox;
import javax.swing.JFrame;

public class NewTicket extends JFrame implements ActionListener {
	
	JComboBox petList = null;
	
	public NewTicket() {
		setSize(400,400);
		setVisible(true);
		
		
		this.setLayout(new FlowLayout());
		
		String[] petStrings = { "1-Kyle", "2-Graham" };


		petList = new JComboBox(petStrings);
		
		this.add(petList);
		
		JButton insert = new JButton("Insert");
		insert.addActionListener(this);
		
		add(insert);
		
	}
	public static void main(String[] args) {
		// TODO Auto-generated method stub
        new NewTicket();
	}
	@Override
	public void actionPerformed(ActionEvent arg0) {
		// TODO Auto-generated method stub
		
		// id
		//   1-Kyle
		//   2-Graham
		// insert into the database
		
		String selected = petList.getSelectedItem().toString();
		
		int dash = selected.indexOf("-");
		String id = selected.substring(0, dash);
		System.out.println(id);
		
		
		
	}

}
