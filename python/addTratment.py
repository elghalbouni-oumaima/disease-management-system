from tkinter import * 
from tkinter import messagebox
from tkinter import ttk
import mysql.connector
from dotenv import load_dotenv
from pathlib import Path
import os
import sys

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
        self.geometry("600x580")
        self.title("Patient Diagnosis")
        # Force la fenêtre à s'afficher en premier plan
        self.attributes('-topmost', 1)
        self.after(500, lambda: self.attributes('-topmost', 0))  # Désactive après 500ms
       
        self.header_label = Label(self,text="📝 Patient Treatments Form", font=("Arial", 16, "bold"),bg="#4468f8") 
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
            self.username_entry.insert(0,self.doctorID)
            self.username_entry.grid(row=0, column=1)

        self.treatmnet_frame=LabelFrame(self,text="👨‍💼 Treatments : ", font=("Arial", 13, "bold"),borderwidth=6, relief="groove")
        self.treatmnet_frame.pack(padx=20,pady=20)
        self.submitButton=Button(self,bg="#4468f8",  font=("Arial", 8, "bold"),
                                      relief=RAISED, bd=5,text="Submit",width=20,command=self.submitInfo)
        self.submitButton.pack(side="right", padx=30,pady=0)
        self.cancelButton=Button(self,bg="red", fg="black", font=("Arial", 8, "bold"),
                                      relief=RAISED, bd=5,text="Cancel",width=20,command=self.cancel)
        self.cancelButton.pack(side="left", padx=30)
        self.create_treatment_frame()

    def create_treatment_frame(self):
        
        treatment_label_frame=Frame(self.treatmnet_frame)
        treatment_note_frame=Frame(self.treatmnet_frame)
        self.diagID_label = Label(treatment_label_frame, text="Diagnosis ID* : ")
        self.diagID_entry = Entry(treatment_label_frame,width=50,foreground="#85929e")
        
        self.treatment_label = Label(treatment_label_frame, text="Treatment Name* : ")
        self.treatment_entry = Entry(treatment_label_frame,width=60)
        self.treatment_note_label = Label(treatment_note_frame, text="Treatment Note :")
        self.treatment_note_entry=Text(treatment_note_frame,height=2,width=40)
       
        self.liststocktraitement=[]
        Button(self.treatmnet_frame, text="➕",command= lambda: self.add_Item(self.liststocktraitement)).grid(row=1,column=1,sticky="w")

        qst_frame=Frame(self.treatmnet_frame)
        
        self.medication_qst = Label(qst_frame, text="Does this treatment require medication?")
        
        # Variable to store selected option
        self.selected_option = StringVar(value="no")
        
        self.canvas = Canvas(self.treatmnet_frame, bg="#dfe7fd")
        self.frame = Frame(self.canvas, bg="#dfe7fd")
        self.scrollbar = Scrollbar(self.treatmnet_frame, orient="vertical", command=self.canvas.yview)

        self.canvas.configure(yscrollcommand=self.scrollbar.set, height=150, width=480)
        
        self.canvas.grid(row=3, column=0)  # Aligner le canvas à gauche
        self.scrollbar.grid(row=3, column=1, sticky="ns")  # Ajuster la scrollbar à droite

        # Créer la fenêtre à l'intérieur du canvas et l'aligner à gauche
        self.canvas.create_window((0, 0), window=self.frame, width=480)

        # Mise à jour de la géométrie du frame
        self.frame.update_idletasks()
        self.frame.bind("<Configure>", lambda e: self.canvas.configure(scrollregion=self.canvas.bbox("all")))

        # Ajout d'un widget de test pour vérifier l'alignement
        test_label = Label(self.frame, text="Add treatment here", bg="#dfe7fd")
        test_label.pack(anchor="w")  # Alignement gauche avec marge minimale

        # Create Radiobuttons
        radio1 = ttk.Radiobutton(qst_frame, text="Yes", variable=self.selected_option, value="yes", command=self.on_selection)
        radio2 = ttk.Radiobutton(qst_frame, text="No", variable=self.selected_option, value="no ", command=self.on_selection)

        treatment_label_frame.grid(row=0,column=0)
        qst_frame.grid(row=1,column=0)
        treatment_note_frame.grid(row=4,column=0)

        self.diagID_label.grid(row=0,column=0)
        self.diagID_entry.grid(row=0,column=1)
        self.treatment_label.grid(row=2,column=0)
        self.treatment_entry.grid(row=2,column=1)
        for widget in treatment_label_frame.winfo_children():
            widget.grid_configure(padx=0,pady=10,sticky="w")
            if isinstance(widget,(Entry,ttk.Combobox,Text)):
                widget.bind("<Return>", focus_next_widget)


        self.treatment_note_label.grid(row=0,column=0)
        self.treatment_note_entry.grid(row=0,column=1)

        self.medication_qst.grid(row=0,column=0,sticky="w")
        radio1.grid(row=0,column=1,sticky="w",padx=10)
        radio2.grid(row=0,column=2,sticky="w",padx=10)
        for widget in self.treatmnet_frame.winfo_children():
                widget.grid_configure(padx=10,pady=10,sticky="w")



    def on_selection(self):
      
        if  self.treatment_entry.get():

        # Check if the treatment entry is filled
            if self.selected_option.get() == "yes" :
                if  hasattr(self, 'treatment_duree_frame'):
                    self.treatment_duree_frame.grid_forget()
                # If medication_frame doesn't exist, create it
                if not hasattr(self, 'medication_frame'):
                    self.medication_frame = Frame(self.treatmnet_frame)
                    self.medication_frame.grid(row=2, column=0)

                    medications = [
                        "Doliprane - Acetaminophen ",
                        "Dafalgan - Acetaminophen ",
                        "Levothyrox - Levothyroxine",
                        "Kardegic - Aspirin ",
                        "Efferalgan - Acetaminophen ",
                        "Zymad - Vitamin D",
                        "Ventoline - Salbutamol",
                        "Spasfon - Phloroglucinol"
                    ]

                    self.medi_name_label = Label(self.medication_frame, text="Medication Name* : ")
                    self.medi_dosage_label = Label(self.medication_frame, text="Dosage* : ")
                    self.medi_duree_label = Label(self.medication_frame, text=" Duration* : ")

                    self.medi_name_entry = ttk.Combobox(self.medication_frame, width=20, values=medications)
                    self.medi_dosage_entry = Entry(self.medication_frame, width=20)
                    self.medi_duree_entry = Entry(self.medication_frame)
                
                    self.medi_name_label.grid(row=0, column=0)
                    self.medi_name_entry.grid(row=1, column=0)
                    self.medi_dosage_label.grid(row=0, column=1)
                    self.medi_dosage_entry.grid(row=1, column=1)

                    self.medi_duree_label.grid(row=0, column=2)
                    self.medi_duree_entry.grid(row=1, column=2)
                    for widget in self.medication_frame.winfo_children():
                        widget.grid_configure(padx=5,pady=5)
                        if isinstance(widget,(Entry,ttk.Combobox,Text)):
                            widget.bind("<Return>", focus_next_widget)
                    
                else:
                    # If medication_frame exists but is hidden, show it
                    self.medication_frame.grid(row=2, column=0)
            else:
                # Handle case for treatment without medication
                if hasattr(self, 'medication_frame'):
                    self.medication_frame.grid_forget()  # Hide medication frame

                
                if not hasattr(self, 'treatment_duree_frame'):
                    self.treatment_duree_frame=Frame(self.treatmnet_frame)
                    self.medi_duree_label = Label(self.treatment_duree_frame, text="Treatment duration* : ")
                    self.medi_duree_entry = Entry(self.treatment_duree_frame,width=50)
                    self.treatment_duree_frame.grid(row=2,column=0,sticky="w")
                    self.medi_duree_label.grid(row=0,column=0,sticky="w")
                    self.medi_duree_entry.grid(row=0,column=1,sticky="w")
                else:
                    self.treatment_duree_frame.grid(row=2, column=0,sticky="w")

    


    def add_Item(self,lststock):
        var1=self.treatment_entry.get().strip()
        var2=self.medi_duree_entry.get().strip()
        if self.selected_option.get()=="yes":
            var3=self.medi_name_entry.get().strip()
            var4=self.medi_dosage_entry.get()
        else:
           var3='none'
           var4='none'
               
        label="[Treatment name :  " +var1 +"]" +"  [Medication :  "+var3  +"] " +" [Dosage :  "+var4 +"] " +"  [Treatment duration :  "+ var2 +"] "
        text ={'Treatment name' : var1  ,'Medication' :  var3,'Dosage': var4,'Treatment duration' :  var2 }

       
        if var1 and var2 and var3 and var4:
            lststock.insert(0, text)
            # Frame pour chaque élément
            item_frame = Frame(self.frame, pady=5,bg="#dfe7fd")
            item_frame.pack(fill="x")
             # Bouton supprimer
           
            Button(item_frame, text='❌', font=("Arial", 7),command=lambda: self.deleteItem(item_frame,text,lststock)).pack(side="left", padx=10)

            Label(item_frame,text=label,font=("Arial", 8)).pack(side="left", padx=10)
           
            
    def deleteItem(self, item,text,liststock):
        
        item.destroy()  # Supprimer l'élément de la liste
        for itm in liststock:
            if(itm==text) :liststock.remove(itm)

        

    def verify_champ(self):
        return self.diagID_entry.get() and  self.liststocktraitement
      
    
    def submitInfo(self):
        j=0
        mydb = connection_to_db()
        mycursor=mydb.cursor()
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
        print(doctorID)
        mycursor.execute("select diagnosis_id from diagnosis where doctor_id=%s",(self.doctorID,))
        res=mycursor.fetchall()
        print(f"  kio {int(self.diagID_entry.get())}")
        for elm in res:
            print(int(elm[0]))
            if int(elm[0])==int(self.diagID_entry.get()):
               j=1 
               print(j)
                
#       
        if self.username_entry!=None and  i!=0:
            messagebox.showerror("Error", "The username does not exist. Please check the field.")
        elif j==0:
            messagebox.showerror("Error", "Diagnosis ID does not exist. Please check the field.")
        elif(not self.verify_champ()):
            messagebox.showerror("Error", "Information is missing. Please check the fields.")

        else:
            # On récupère le patient_id lié au diagnosis_id
            mycursor.execute(
                "SELECT patient_id FROM diagnosis WHERE diagnosis_id = %s",
                (self.diagID_entry.get(),)
            )
            #list de tuples
            result = mycursor.fetchall()
            Traitnote=self.treatment_note_entry.get("1.0", "end-1c") if self.treatment_note_entry.get("1.0", "end-1c") else 'none'
            
            try:
            # On ajoute les traitements pour le patient/diagnostic

                for itm in self.liststocktraitement:
                    mycursor.execute(
                        "INSERT INTO treatments (patient_id, diagnosis_id, treatment_name, medication_name, dosage, treatment_notes, treatment_duration) "
                        "VALUES (%s, %s, %s, %s, %s, %s, %s)",
                        (
                            result[0][0],
                            self.diagID_entry.get(),
                            itm['Treatment name'],
                            itm['Medication'],
                            itm['Dosage'],
                            Traitnote,
                            itm['Treatment duration']
                        )
                    )

                    mydb.commit()  # Très important pour valider les insertions
                    messagebox.showinfo("Success", "Treatments saved successfully.")
            except Exception as e:
                mydb.rollback()  # 🔄 En cas d'erreur, on annule tout
                messagebox.showerror("Error", f"Failed to save: {e}") 
               
            mycursor.close()
            mydb.close()         
        
    def cancel(self):
        for widget in self.treatmnet_frame.winfo_children():
            if isinstance(widget,Frame):
                for itm in widget.winfo_children():
                    if isinstance(itm,Entry):
                        itm.delete(0,END)
                    if isinstance(itm,Text):
                        itm.delete("1.0",END)
 
            if isinstance(widget,Entry):
                widget.delete(0,END)
            if isinstance(widget,Text):
                widget.delete("1.0",END)
        self.liststocktraitement.clear()
        for widget in self.canvas.winfo_children():
            widget.destroy() 

if __name__ == '__main__':
    if(len(sys.argv)>1):
        doctorID=sys.argv[1]
        print("l'arg est recupere")
    else:
        doctorID = ""
        print("Aucun argument reçu.")    
    
    window=Mainwindow(doctorID)
    window.mainloop()

