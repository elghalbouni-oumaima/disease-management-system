from tkinter import * 
from tkinter import ttk
from tkinter import messagebox
from tkcalendar import DateEntry
import mysql.connector
import sys

from dotenv import load_dotenv
from pathlib import Path
import os

def connection_to_db():
    if not load_dotenv(dotenv_path = Path('C:/xampp/htdocs/managementdesease/Login/.env')):
        print( "Erreur : Impossible de charger le fichier .env")
           
    try:
        mydb = mysql.connector.connect(
            host=os.getenv('localhost'),
            user=os.getenv('DB_user_name'),
            password=os.getenv('DB_PASSWORD'),
            database="diseasemanagement"
        )

        return mydb
    except mysql.connector.Error as err:
        print(f"Erreur : {err}")

def focus_next_widget(event):
        event.widget.tk_focusNext().focus()
        return "break"  # empêche le comportement par défaut

class Mainwindow(Tk):
    def __init__(self,doctorID):
        super().__init__()
        self.doctorID=doctorID
        print(self.doctorID)
        self.geometry("600x470")
        self.title("Patient Diagnosis")
        # Force la fenêtre à s'afficher en premier plan
        self.attributes('-topmost', 1)
        self.after(500, lambda: self.attributes('-topmost', 0))  # Désactive après 500ms
        self.header_label = Label(self,text="📝 Patient Diagnosis Form", font=("Arial", 18, "bold"),bg="blue",) 
        self.header_label.pack(fill="x")
        mydb = connection_to_db()
        mycursor=mydb.cursor()
        mycursor.execute("select role from doctor where username=%s",(self.doctorID,))
        result = mycursor.fetchone() # Get the first row of the result
        self.username_entry=None
        if(result and result[0]=="admin"):
            username_frame=Frame(self)
            username_frame.pack(pady=10)
            Label(username_frame,text="Doctor username : ",fg="#4468f8",font=("Arial", 10, "bold")).grid(row=0,column=0,padx=20)
            self.username_entry=Entry(username_frame,width=34)
            self.username_entry.grid(row=0, column=1)
        self.diag_frame=LabelFrame(self,text="👨‍💼 Diagnostic : ", font=("Arial", 13, "bold"),borderwidth=6, relief="groove")
        self.diag_frame.pack(padx=20,pady=10)
        self.submitButton=Button(self,bg="blue", fg="white", font=("Arial", 9, "bold"),
                                     text="Submit",width=15,command=self.submitInfo)
        self.submitButton.pack(side="right", padx=30)

        self.cancelButton=Button(self,bg="red", fg="white", font=("Arial", 9, "bold"),
                                     text="Cancel",width=15,command=self.cancel)
        self.cancelButton.pack(side="left", padx=30)

        
        self.create_diag_frame()

    def create_diag_frame(self):
        
        self.patientId = Label(self.diag_frame, text="Patient id* : ")
        self.diagnostic = Label(self.diag_frame, text="Diagnostic* : ")
        self.diagDate_label = Label(self.diag_frame, text="Diagnostic Date* : ")
        self.diagnostic_note = Label(self.diag_frame, text="Diagnostic Note :")

        self.diagDate_entry= DateEntry(self.diag_frame,width=20,date_pattern="yyyy-mm-dd")
        self.diag_note_entry=Text(self.diag_frame,height=2,width=40)
        self.patientId_entry = Entry(self.diag_frame,width=50,foreground="#85929e")
        self.diagnostic_entry = Entry(self.diag_frame,width=40)

        self.patientId.grid(row=0,column=0,sticky="w")
        self.patientId_entry.grid(row=0,column=1,sticky="w")

        self.diagnostic.grid(row=1,column=0,sticky="w")
        self.diagnostic_entry.grid(row=1,column=1,sticky="w")

        self.diagDate_label.grid(row=2,column=0,sticky="w")
        self.diagDate_entry.grid(row=2,column=1,sticky="w")

        self.diagnostic_note.grid(row=3,column=0,sticky="w")
        self.diag_note_entry.grid(row=3,column=1,sticky="w")

        #self.treatment.grid(row=2,column=0)
        #self.treatment_text.grid(row=3,column=1)
        for widget in self.diag_frame.winfo_children():
            widget.grid_configure(padx=20,pady=20)
            if isinstance(widget,(Entry, ttk.Combobox)):
                widget.bind("<Return>", focus_next_widget)
        
    def verify_champ(self):
        return self.patientId_entry.get() and self.diagnostic_entry.get() 

    
    def submitInfo(self):
        mydb = connection_to_db()
        mycursor=mydb.cursor()
        j=0
        if(self.username_entry!=None):
                mycursor.execute("select username from doctor")
                result=mycursor.fetchall()
                for row in result:
                    if self.username_entry.get()  in row:
                        self.doctorID=self.username_entry.get()
                        i=0
                        break
                    else:i=1
        print(self.doctorID)
        mycursor.execute("select id from patient where doctor_id=%s",(self.doctorID,))
        res=mycursor.fetchall()
        print(f"  kio {int(self.patientId_entry.get())}")
        for elm in res:
            print(int(elm[0]))
            if int(elm[0])==int(self.patientId_entry.get()):
               j=1 
               print(j)
                
#       
        if self.username_entry!=None and  i!=0 :
            messagebox.showerror("Error", "The username does not exist. Please check the field.")
        elif(not self.verify_champ()):
            messagebox.showerror("Error", "Information is missing. Please check the fields.")

        elif j==0:
            messagebox.showerror("Error", "Patient ID does not exist. Please check the field.")
        
        else:
            mydb=connection_to_db()
            mycursor=mydb.cursor()
            diagnote=self.diag_note_entry.get("1.0", "end-1c") if self.diag_note_entry.get("1.0", "end-1c") else 'none'
            try:
                mycursor.execute(
                    " insert into diagnosis (patient_id,doctor_id,diagnosis_name,diagnosis_notes,diagnosis_date) values(%s,%s,%s,%s,%s)",
                    (
                        self.patientId_entry.get(),
                        self.doctorID,self.diagnostic_entry.get(),
                        diagnote,
                        self.diagDate_entry.get()
                    )
                            )
                mydb.commit()
                messagebox.showinfo("Success", "Diagnosis saved successfully.")

            except Exception as e:
                mydb.rollback()  # 🔄 En cas d'erreur, on annule tout
                messagebox.showerror("Error", f"Failed to save: {e}") 
            finally:
                mycursor.close()
                mydb.close()
        
    def cancel(self):
        for widget in self.diag_frame.winfo_children():
            if isinstance(widget,Entry):
                widget.delete(0,END)
            if isinstance(widget,Text):
                widget.delete("1.0",END)
            

        
if __name__ == '__main__':
    if(len(sys.argv)>1):
        doctorID=sys.argv[1]
        print("l'arg est recupere")
    else:
        doctorID = ""
        print("Aucun argument reçu.")    
    
    window=Mainwindow(doctorID)
    window.mainloop()

