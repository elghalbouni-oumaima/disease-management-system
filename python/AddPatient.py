from tkinter import *
#from PIL import Image, ImageTk
from tkinter import ttk
from tkcalendar import DateEntry
from tkinter import messagebox
from pyisemail import is_email
import mysql.connector
import re
from dotenv import load_dotenv
from pathlib import Path
import os
import datetime
import sys


def validate_email(email):
    print(email)
    pattern=r"^[\w\.-]+@[\w.-]+\.\w+$"
    print(re.match(pattern,email))
    if re.match(pattern,email):
        return True
    return False

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

    #finally:
        # Fermeture du curseur et de la connexion
        #if 'mycursor' in locals() and mycursor is not None :
            #mycursor.close()
        #if 'mydb' in locals() and mydb is not None :
            #mydb.close()


def focus_next_widget(event):
    event.widget.tk_focusNext().focus()
    return "break"  # empêche le comportement par défaut

class create_widget():

    def __init__(self,master,doctor_id):
        self.doctor_id=doctor_id
        self.master = master
        master.geometry("1000x600")
        master.title("Patient Registration")
        
        # Force la fenêtre à s'afficher en premier plan
        master.attributes('-topmost', 1)
        master.after(500, lambda: master.attributes('-topmost', 0))  # Désactive après 500ms


        
        # 1. Configuration du Canvas et de la Scrollbar
        self.canvas_principale = Canvas(master, bg="white", highlightthickness=0)
        self.scrollbar = ttk.Scrollbar(master, orient="vertical", command=self.canvas_principale.yview)
        self.main_frame = Frame(self.canvas_principale, bg="white")

        self.canvas_principale.configure(yscrollcommand=self.scrollbar.set,height=40)
        self.canvas_principale.pack(side="left", fill="both", expand=True)
        self.scrollbar.pack(side="right", fill="y")

        self.window = self.canvas_principale.create_window((0, 0), window=self.main_frame, anchor="nw")

        # 2. Configuration du Frame pour le Scrolling
        self.main_frame.bind("<Configure>", self.on_frame_configure)
        self.canvas_principale.bind("<Configure>", self.on_canvas_configure)

        master.grid_rowconfigure(2, weight=1)
        master.grid_columnconfigure(0, weight=1)
        self.canvas_principale.bind_all("<MouseWheel>", self._on_mousewheel)
        self.canvas_frame=[]

        mydb = connection_to_db()
        mycursor=mydb.cursor()
        mycursor.execute("select role from doctor where username=%s",(doctor_id,))
        result = mycursor.fetchone() # Get the first row of the result
        self.username_entry=None
        if(result and result[0]=="admin"):
            username_frame=Frame(self.main_frame)
            username_frame.grid(row=0,column=0,sticky="ew")
            Label(username_frame,text="Doctor username : ",fg="#4468f8",font=("Arial", 10, "bold")).grid(row=0,column=0,padx=20)
            self.username_entry=Entry(username_frame,width=34)
            self.username_entry.insert(0,self.doctor_id)

            self.username_entry.grid(row=0, column=1)
            
            
        self.info_frame = LabelFrame(self.main_frame,text="👨‍💼 Personnel Information : ",cursor="hand2",takefocus=True,font=("Arial", 13, "bold"),borderwidth=6,)
        self.basicMedicalInf_frame = LabelFrame(self.main_frame,text="🏥Basic Medical Information :", font=("Arial", 12, "bold"),borderwidth=6, )
        self.Symptom_frame = LabelFrame(self.main_frame,text="🌡️Symptoms :", font=("Arial", 12, "bold"),borderwidth=6, relief="groove")
        self.diag_frame=LabelFrame(self.main_frame,text="👨‍💼 Diagnostic : ", font=("Arial", 13, "bold"),borderwidth=6, relief="groove")
        self.treatmnet_frame = LabelFrame(self.main_frame,text="Treatment :", font=("Arial", 12, "bold"),borderwidth=6, relief="groove")

        btn_frame=Frame(self.main_frame,bg="white")
        btn_frame.grid(row=5, padx=20,sticky="w",pady=10)
        self.header_label = Label(self.main_frame, font=("Arial", 20, "bold"),) 
        self.header_label.grid(row=1, column=0,pady=20)

        btn_frame=Frame(self.main_frame,bg="white")
        btn_frame.grid(row=8, padx=20,sticky="w",pady=10)
        
        self.submit_button = Button(btn_frame,bg="blue",width=15, fg="white",font=("Arial", 11, "bold"),
                                     command=self.show_inf) 
        self.submit_button.grid(row=0, column=0)

        self.cancel_button = Button(btn_frame,bg="red",fg="white", text= "Cancel", font=("Arial", 11, "bold"),
                                     command=self.cancel) 
        self.cancel_button.grid(row=0, column=1,padx=20)

        self.patient_info ={} 
        self.create_buttons()
        self.addPatient() # Afficher le formulaire d'ajout de patient par défaut

    
         # 3. Redimensionnement du Canvas/Frame
    def on_frame_configure(self, event):
        self.canvas_principale.configure(scrollregion=self.canvas_principale.bbox("all"))

    def on_canvas_configure(self, event):
        canvas_width = event.width
        self.canvas_principale.itemconfig(self.window, width=canvas_width)

    def _on_mousewheel(self,event):
        self.canvas_principale.yview_scroll(int(-1 * (event.delta / 120)), "units")

    def verify_info_add(self):
        print(self.listStockSymptom)
        return self.physical_condition_entry.get() and self.drug_entry.get() and self.smoking_combobox.get()  and self.chronic_diseases_entry.get() and self.first_name_entry.get() and self.listStockSymptom and self.last_name_entry.get() and   self.birth_day_entry.get()  and self.sex_combobox.get() 

    def cancel(self):
        self.listStockSymptom.clear()
       
        # delete info_frame component
        for widget in self.info_frame.winfo_children():
            if isinstance(widget,Entry):
                widget.delete(0,END)
            if isinstance(widget,Text):
                widget.delete("1.0",END)
            

        # delete basicMedicalInf_frame component
        for widget in self.basicMedicalInf_frame.winfo_children():
            if  isinstance(widget,Frame):
                for itm in widget.winfo_children():
                    if isinstance(itm,(Entry,ttk.Combobox,Spinbox)):
                        itm.delete(0,END)
            if isinstance(widget,(Entry,ttk.Combobox,Spinbox)):
                widget.delete(0,END)
            if isinstance(widget,Text):
                widget.delete("1.0",END)
        
        # delete Symptome_frame componenet
        for itm in  self.canvas_frame:
            itm.forget()
        
        self.canvas_frame.clear()

        # DELETE diagnosis components
        for widget in self.diag_frame.winfo_children():
            for itm in widget.winfo_children():
                if isinstance(widget,Entry):
                    widget.delete(0,END)
        # delete treatement componenets
        if  hasattr(self, 'medication_frame'):
            self.medication_frame.grid_forget()
        if  hasattr(self, 'treatment_duree_frame'):
            self.treatment_duree_frame.grid_forget()
        self.selected_option.set(None)
        for widget in self.treatmnet_frame.winfo_children():
            if isinstance(widget,Entry):
                widget.delete(0,END)
            elif isinstance(widget,Text):
                widget.delete('1.0',END)
            elif isinstance(widget,Frame):
                for widget in widget.winfo_children():
                    if isinstance(widget,Entry):
                        widget.delete(0,END)
                    elif isinstance(widget,Text):
                        widget.delete('1.0',END)
        self.liststocktraitement.clear() 


    
    
    def verify_diagnostic_add(self):
        return self.patientId_entry.get() and self.diagnostic_entry.get() and self.liststocktraitement       
    def show_inf(self):
        today = datetime.datetime.now()
        today = today.strftime("%Y-%m-%d")
        if self.submit_button.cget('text')=="Save":

            if(self.username_entry!=None):
                mydb = connection_to_db()
                mycursor=mydb.cursor()
                mycursor.execute("select username from doctor")
                result=mycursor.fetchall()
                for row in result:
                    if self.username_entry.get()  in row:
                        self.doctor_id=self.username_entry.get()
                        i=0
                        break
                    else:i=1
            if i!=0:
                messagebox.showerror("Error", "The username does not exist. Please check the field.")

                    

            elif not self.verify_info_add():
                messagebox.showerror("Error", "Information is missing. Please check the fields.")
            elif  not validate_email(self.email_entry.get()) and self.email_entry.get():
                messagebox.showerror("Erreur", "Email address is invalide.")
            else:
               
                values=[]
                keys=[]
                
                for widget in self.info_frame.winfo_children():
                    if isinstance(widget, (Entry, ttk.Combobox)):  # Check if the widget is Entry or Combobox
                        if widget.get() : # Get the value from Entry or Combobox
                            values.append(widget.get())
                             
                        else: values.append('None')
                    if isinstance(widget, Label):
                        keys.append(widget.cget("text")) 
                        
                for widget in self.basicMedicalInf_frame.winfo_children():
                    if isinstance(widget, (Entry, ttk.Combobox)):  # Check if the widget is Entry or Combobox
                        if widget.get() : # Get the value from Entry or Combobox
                            values.append(widget.get())
                        else: values.append('None')
                    elif isinstance(widget, Frame):
                        for child in widget.winfo_children():
                            if isinstance(child, (Entry, ttk.Combobox)):  # Check if the widget is Entry or Combobox
                                if child.get() : # Get the value from Entry or Combobox
                                    values.append(child.get())
                                else: values.append('None')
                            elif isinstance(child, Label):
                                keys.append(child.cget("text")) 
                    elif isinstance(widget, Text):  # Check if the widget is Entry or Combobox
                        if widget.get("1.0", "end-1c") : # Get the value from Entry or Combobox
                            values.append(widget.get("1.0", "end-1c"))
                        else: values.append('None')
                    elif isinstance(widget, Label):
                        keys.append(widget.cget("text")) 
                self.patient_info = dict(zip(keys, values))
                
                print(keys)
                print(f"the size is : ",len(keys))
                print(values)
                print(f"the size is : ",len(values))

                self.formulaire=Tk()
                self.formulaire.geometry("700x500")
                
                Label(self.formulaire,text=f"Registration Date : {today}",font=("Arial", 11, "bold"),foreground='white',background='#44b0f8',width=40).grid(row=0,column=0,sticky="ew",pady=10)
                
                self.formulaire.columnconfigure(0, weight=1)  # Permet l'expansion du label      
                Inf_erg_frame = LabelFrame(self.formulaire,relief="groove",borderwidth=4,text="Patient Personal and Health Information",font=("Arial", 14, "bold"),foreground='#44b0f8')
                Inf_erg_frame.grid(row=1,column=0,pady=8)
                

                canvas = Canvas(self.formulaire)
                frame = Frame(canvas)
                scrollbar = Scrollbar(self.formulaire, orient="vertical", command=canvas.yview)
                canvas.configure(yscrollcommand=scrollbar.set,height=80)
                canvas.grid(row=3, column=0, columnspan=3)  # Étendre le canvas
                scrollbar.grid(row=3, column=3, sticky="ns")  # Barre de scroll à droite
                
                canvas.create_window((0, 0), window=frame, anchor="nw")
                frame.bind("<Configure>", lambda e: canvas.configure(scrollregion=canvas.bbox("all")))
                Label(self.formulaire,text="Symptoms :",font=("Arial", 12, "bold underline")).grid(row=2,column=0,sticky="w",pady=10,padx=4)

                
                """Display the dictionary as a table inside the canvas."""
                # Get column names from the first dictionary
                if self.listStockSymptom:
                    headers = list(self.listStockSymptom[0].keys())

                    # Create header labels
                    for col, header in enumerate(headers):
                        Label(frame, text=header, font=("Arial", 10), borderwidth=1, relief="solid",
                            padx=30, pady=5,bg="#dfe7fd").grid(row=0, column=col)

                    # Create table rows from dictionary values
                    for row, item in enumerate(self.listStockSymptom, start=1):
                        for col, key in enumerate(headers):
                            Label(frame, text=item[key], font=("Arial", 9), borderwidth=1, relief="solid",
                                padx=5, pady=5).grid(row=row, column=col, sticky="nsew")

                
                sql="select max(id) from patient"
                mydb = connection_to_db()
                mycursor=mydb.cursor()
                mycursor.execute(sql)
                result = mycursor.fetchone() # Get the first row of the result
                self.patient_id = result[0]+1 # Extract the value from the tuple
                Label(Inf_erg_frame,text="Patient ID : ",foreground="red").grid(row=1,column=0,sticky="w",padx=2)
                Label(Inf_erg_frame,text=self.patient_id,foreground="red").grid(row=1,column=1,sticky="w",padx=2)


                Label(Inf_erg_frame,text='').grid(row=0,column=2,padx=20)
                i=2
                j=0
                Label(Inf_erg_frame,text="Personnel Information :",font=("Arial", 10, "bold underline"),bg="#aeb6bf").grid(row=0,column=j,sticky="w",pady=8)
                for value in self.patient_info:
                    if value=="Height :":
                        j=3
                        i=1
                        Label(Inf_erg_frame,text="Basic Medical Information :",bg="#aeb6bf",font=("Arial", 10, "bold underline")).grid(row=0,column=j,sticky="w")
                    Label(Inf_erg_frame,text=value).grid(row=i,column=j,sticky="w",padx=2)
                    i+=1
                i=2
                j=1
                for value in self.patient_info:
                    if value=="Height :":
                        j=4
                        i=1
                    Label(Inf_erg_frame,text=self.patient_info.get(value)).grid(row=i,column=j,sticky="w",padx=2)
                    i+=1

                return_btn=Button(self.formulaire,text="Return",
                                         command=self.return_fct,
                                          bg="red", fg="white", font=("Arial", 9, "bold"),
                                          relief=RAISED, bd=3)
                return_btn.grid(row=4,column=0)
                Register_btn=Button(self.formulaire,text="Save",
                                         command=self.register_info,
                                          bg="green", fg="white", font=("Arial", 9, "bold"),
                                          relief=RAISED, bd=3)
                Register_btn.grid(row=4,column=1)
        else:
            if not self.verify_diagnostic_add():
                messagebox.showerror("Error", "Information is missing. Please check the fields.")
            else:
                mydb=connection_to_db()
                cursor=mydb.cursor()
                cursor.execute("select * from patient where id =%s and doctor_id=%s",(self.patientId_entry.get().strip(),self.doctor_id))
                if not cursor.fetchone():
                    messagebox.showerror("Error", "The patient id does not exist.")
                else:
                        print(self.liststocktraitement[0].values())
                       
                        try:
                            sql_diag = """
                            INSERT INTO diagnosis (patient_id, diagnosis_name, doctor_id, diagnosis_date, diagnosis_notes)
                            VALUES (%s, %s, %s, %s, %s)
                            """
                            diag_note = self.diag_note_entry.get("1.0", "end-1c") or 'None'
                            cursor.execute(sql_diag, (
                                self.patientId_entry.get().strip(),
                                self.diagnostic_entry.get().strip(),
                                self.doctor_id,
                                today,
                                diag_note
                            ))
                            cursor.execute('select max(diagnosis_id) from diagnosis')
                            diag_id=cursor.fetchone()[0]
                            
                            sql_treat = """
                            INSERT INTO treatments (patient_id, treatment_name, medication_name, dosage, treatment_duration,diagnosis_id)
                            VALUES (%s, %s, %s, %s, %s, %s)
                            """
                            for dic in self.liststocktraitement:
                                values = list(dic.values())
                                values.insert(0, self.patientId_entry.get().strip())  # patient_id en premier
                                values.append(diag_id)
                                cursor.execute(sql_treat, values)

                            mydb.commit()  # ✅ UN SEUL COMMIT ICI
                            messagebox.showinfo("Success", "The modification was registered successfully.")
                        except Exception as e:
                            mydb.rollback()  # 🔄 En cas d'erreur, on annule tout
                            messagebox.showerror("Error", f"Failed to save: {e}") 
                 
                mydb.close()
                cursor.close()
                    




    def return_fct(self):
        self.formulaire.destroy()
    
    def register_info(self):
        mydb = connection_to_db()
        mycursor=mydb.cursor()
        sql = """
                        INSERT INTO patient (
                            First_name, Last_name, birthdate, email, contact_phone, Gender, address, 
                            height_cm, weight_kg, smoking_alcohol, blood_type, bmi, physical_condition, chronic_diseases, medical_history,
                            surgical_history, drug_allergies, doctor_id, registration_day
                        ) 
                        VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s);
        """ 
        values=list(self.patient_info.values()) 
        today = datetime.datetime.now()
        today = today.strftime("%Y-%m-%d")
        values.append(self.doctor_id)
        values.append(today)                
                # Execute query
                
        mycursor.execute(sql, values)
        mydb.commit()
                    # Check if rows were affected
        if mycursor.rowcount > 0:
            messagebox.showinfo("Information", "Patient Data was inserted successfully!")
        else:
            print("No data was inserted.")

       
        sql="insert into symptoms (patient_id,symptom_name,start_date,severity,registration_date) values (%s,%s,%s,%s,%s)"
        for dict in self.listStockSymptom:
            values=list(dict.values())
            values.insert(0,self.patient_id)
            values.append(today)
            print(values)
            mycursor.execute(sql, values)
        
        # Close connections
        mydb.commit()
        mycursor.close()
        mydb.close()

            
    def create_basicMedicalInf_frame(self):
        drug_Allergy =["No Drug Allergy","Penicillin", "Aspirin", "Ibuprofen", "Sulfa drugs", "Tetracycline",
            "Codeine", "Morphine", "Lidocaine", "Amoxicillin", "Cephalosporins",
            "NSAIDs", "Quinolones", "Macrolides", "Anticonvulsants", "ACE inhibitors",
            "Beta-blockers", "Local anesthetics", "Insulin", "Contrast dyes", "Statins"]
        
        Chronic_Diseases=["No Chronic Disease","Hypertension", "Coronary Artery Disease", "Chronic Heart Failure", "Arrhythmia", "Atherosclerosis", "Stroke", "Peripheral Artery Disease",
        "Asthma", "Chronic Obstructive Pulmonary Disease", "Chronic Bronchitis", "Bronchiectasis", "Pulmonary Fibrosis", "Sleep Apnea Syndrome",
        "Type 1 Diabetes", "Type 2 Diabetes", "Hypothyroidism", "Hyperthyroidism", "Cushing's Syndrome", "Metabolic Syndrome", "Obesity",
        "Alzheimer’s Disease", "Parkinson’s Disease", "Multiple Sclerosis", "Amyotrophic Lateral Sclerosis", "Huntington’s Disease", "Peripheral Neuropathy",
        "Crohn’s Disease", "Ulcerative Colitis", "Irritable Bowel Syndrome", "Non-ic Fatty Liver Disease", "Cirrhosis", "Chronic Hepatitis B or C",
        "Chronic Kidney Disease", "Chronic Nephritis", "Recurrent Kidney Stones", "Interstitial Cystitis",
        "Systemic Lupus Erythematosus", "Rheumatoid Arthritis", "Multiple Sclerosis", "Celiac Disease", "Behcet's Disease", "Psoriasis", "Atopic Dermatitis", "Graves' Disease",
        "Osteoporosis", "Sickle Cell Disease", "Thalassemia", "Chronic Migraine", "Fibromyalgia", "Chronic Fatigue Syndrome"
         ]
        physicalCond_options=['Moderate','Weak','Requires Special Care','Active']
        blood_types = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"]
        smoking_options=["yes","no","rarely"]
        # flat, groove, raised, ridge, solid, or sunken
        
        self.height_frame= Frame(self.basicMedicalInf_frame)
        for widget in self.height_frame.winfo_children():
            widget.grid_configure(padx=4)

        self.bloodSmk_frame= Frame(self.basicMedicalInf_frame)
        for widget in self.bloodSmk_frame.winfo_children():
            widget.grid_configure(padx=4)

        self.height_label = Label(self.height_frame, text="Height :")
        self.weight_label = Label(self.height_frame, text="Weight :")
        self.height_entry = Entry(self.height_frame,width=10)
        self.weight_entry = Entry(self.height_frame,width=10)
        self.smoking_label = Label(self.bloodSmk_frame, text="Smoking/Alcohol* : ")
        self.blood_label = Label(self.bloodSmk_frame, text="Blood Type : ")
        
        self.bmi_label = Label(self.basicMedicalInf_frame, text="BMI : ")
        self.physical_condition_label = Label(self.basicMedicalInf_frame, text="Physical Condition* : ")
        self.chronic_diseases_label = Label(self.basicMedicalInf_frame,text="Chronic Diseases* : ")
        self.medical_history_label = Label(self.basicMedicalInf_frame, text="Medical History : ")
        self.surgical_history_label = Label(self.basicMedicalInf_frame, text="Surgical History : ")
        self.drug_label = Label(self.basicMedicalInf_frame, text="Drug Allergy* : ")
        self.bmi_entry = Entry(self.basicMedicalInf_frame,width=50)
        
        
        self.physical_condition_entry = ttk.Combobox(self.basicMedicalInf_frame,width=50,foreground="#85929e",values=physicalCond_options)
        self.physical_condition_entry.set("Activate")
        self.chronic_diseases_entry = ttk.Combobox(self.basicMedicalInf_frame,width=50,values=Chronic_Diseases,foreground="#85929e")
        self.chronic_diseases_entry.set("No Chronic Disease")
        self.medical_history_entry = Text(self.basicMedicalInf_frame,width=50,height=2)
        self.surgical_history_entry = Text(self.basicMedicalInf_frame,width=50,height=2)
        
        self.drug_entry = ttk.Combobox(self.basicMedicalInf_frame,width=50,values=drug_Allergy,foreground="#85929e")
        self.smoking_combobox = ttk.Combobox(self.bloodSmk_frame, state='readonly',width=23,values=smoking_options,foreground="#85929e")
        self.blood_combobox = ttk.Combobox(self.bloodSmk_frame,values=blood_types,width=23,foreground="#85929e")
        self.blood_combobox.set("None")
        self.smoking_combobox.set("no")
        self.drug_entry.set("No Drug Allergy")

        self.physical_condition_label.grid(row=0,column=0)
        self.physical_condition_entry.grid(row=1,column=0)

        self.chronic_diseases_label.grid(row=0,column=1)
        self.chronic_diseases_entry.grid(row=1,column=1)

        self.drug_label.grid(row=2,column=0)
        self.drug_entry.grid(row=3,column=0)

        self.height_frame.grid(row=4,column=0)
        self.height_label.grid(row=0,column=0,padx=20)
        self.weight_label.grid(row=0,column=1,padx=20)
        self.height_entry.grid(row=1,column=0,padx=20)
        self.weight_entry.grid(row=1,column=1,padx=20)

        self.bmi_label.grid(row=2,column=1)
        self.bmi_entry.grid(row=3,column=1)
        self.bloodSmk_frame.grid(row=4,column=1)
        self.blood_label.grid(row=0,column=0)
        self.blood_combobox.grid(row=1,column=0)
        self.smoking_label.grid(row=0,column=1)
        self.smoking_combobox.grid(row=1,column=1)

        self.medical_history_label.grid(row=6,column=0)
        self.medical_history_entry.grid(row=7,column=0)
        self.surgical_history_label.grid(row=6,column=1)
        self.surgical_history_entry.grid(row=7,column=1)
        for widget in self.basicMedicalInf_frame.winfo_children():
            widget.grid_configure(padx=20,pady=6)
            if isinstance(widget,(Entry,ttk.Combobox,Text)):
                widget.bind("<Return>", focus_next_widget)
            if isinstance(widget,Frame):
                   for itm in widget.winfo_children():
                       if isinstance(itm,(Entry,ttk.Combobox,Text)):
                            widget.bind("<Return>", focus_next_widget)

    #####    Symptom_frame    #####
    def create_symptome_frame(self):
        Severity_options=['Severe','Moderate','Mild']
        Symptom_options=['Chest pain','Sore throat','Headache','Shortness of breath','Dizziness','Cough','Fatigue','Nausea','Body aches']
        self.Symptom_name_label = Label(self.Symptom_frame, text="Symptom Name* : ")
        self.Symptom_start_label = Label(self.Symptom_frame, text="Start date* : ")
        self.severity_label = Label(self.Symptom_frame, text="Severity* : ")

        self.Symptom_name_entry = ttk.Combobox(self.Symptom_frame,width=35,values=Symptom_options,foreground="#85929e")
        self.Symptom_name_entry.set("Chest pain")
        self.Symptom_start_entry = DateEntry(self.Symptom_frame,width=35,date_pattern="yyyy-mm-dd")
        self.severity_entry = ttk.Combobox(self.Symptom_frame,width=35,values= Severity_options,foreground="#85929e")
        self.severity_entry.set("Mild")

        self.listStockSymptom = []  # Liste des éléments
        self.add_button = Button(self.Symptom_frame, text="➕",command=lambda: self.add_Item( self.listStockSymptom))
        self.Symptom_name_label.grid(row=0,column=0)
        self.Symptom_name_entry.grid(row=1,column=0)
        self.Symptom_start_label.grid(row=0,column=1)
        self.Symptom_start_entry.grid(row=1,column=1)
        self.severity_label.grid(row=0,column=2)
        self.severity_entry.grid(row=1,column=2)
        self.add_button.grid(row=1,column=3)

       
        # Liste d'éléments avec un Canvas pour le scroll
        self.canvas = Canvas(self.Symptom_frame,bg="#dfe7fd")
        self.frame = Frame(self.canvas,bg="#dfe7fd")
        self.scrollbar = Scrollbar(self.Symptom_frame, orient="vertical", command=self.canvas.yview)
        self.canvas.configure(yscrollcommand=self.scrollbar.set,height=200)
        self.canvas.grid(row=2, column=0, columnspan=3, sticky="nsew")  # Étendre le canvas
        self.scrollbar.grid(row=2, column=3, sticky="ns")  # Barre de scroll à droite
        test_label = Label(self.frame, text="Add symptom here", bg="#dfe7fd")
        test_label.pack(anchor="w")  # Alignement gauche avec marge minimale
        self.canvas.create_window((0, 0), window=self.frame, anchor="nw")
        self.frame.bind("<Configure>", lambda e: self.canvas.configure(scrollregion=self.canvas.bbox("all")))


        for widget in self.Symptom_frame.winfo_children():
            widget.grid_configure(padx=20,pady=4)


    def add_Item(self,lststock):
        if self.submit_button.cget('text')=="Save":
            var1 = self.Symptom_name_entry.get().strip()
            var2 = self.Symptom_start_entry.get().strip()
            var3 = self.severity_entry.get().strip()
            label="[Symptom :  " +var1 +"]" +"  [Date :  "+ var2 +"] "+"  [Intensity :  "+var3 +"]"
            text ={'Symptom' : var1 ,'Date' :  var2 ,'Intensity' :  var3 }
            var4='None'
        else:
            var1=self.treatment_entry.get().strip()
            var2=self.medi_duree_entry.get().strip()
            if self.selected_option.get()=="yes":
               var3=self.medi_name_entry.get().strip()
               var4=self.medi_dosage_entry.get()
            else:
               var3='None'
               var4='None'
               
            label="[Treatment name :  " +var1 +"]" +"  [Medication :  "+var3  +"] " +" [Dosage :  "+var4 +"] " +"  [Treatment duration :  "+ var2 +"] "
            text ={'Treatment name' : var1  ,'Medication' :  var3,'Dosage': var4,'Treatment duration' :  var2 }

       
        if var1 and var2 and var3 and var4:
            lststock.insert(0, text)
 
            # Frame pour chaque élément
            item_frame = Frame(self.frame, pady=5,bg="#dfe7fd")
            item_frame.pack(fill="x")
             # Bouton supprimer
            Button(item_frame, text='❌', command=lambda: self.deleteItem(item_frame,text,lststock)).pack(side="left", padx=10)
           

            Label(item_frame,text=label).pack(side="left", padx=10)
           
            self.Symptom_name_entry.delete(0, END)  # Effacer l'entrée
            self.Symptom_start_entry.delete(0, END)
            self.severity_entry.delete(0, END)
            self.canvas_frame.append(item_frame)


    def deleteItem(self, item,text,liststock):
        print(text)
        item.destroy()  # Supprimer l'élément de la liste
        for itm in liststock:
            if(itm==text) :liststock.remove(itm)

    def create_info_frame(self):
        # flat, groove, raised, ridge, solid, or sunken
        rowf = 0
        self.first_name_label = Label(self.info_frame, text="First Name* : ")
        self.last_name_label = Label(self.info_frame, text="Last Name* : ")
        self.birth_day_label = Label(self.info_frame, text="Birth Date* : ")
        self.sex_label = Label(self.info_frame, text="Gender* : ")
        self.adress_label = Label(self.info_frame, text="Address : ")
        self.phone_label = Label(self.info_frame, text="Phone : ")
        self.email_label = Label(self.info_frame, text="Email address : ")

        #self.symptom_text = Text(self.info_frame, height=10,width=40)
        #self.symptom_label = Label(self.info_frame, text="Symptom : ")
        self.first_name_entry = Entry(self.info_frame,width=50)
        self.last_name_entry = Entry(self.info_frame,width=50)
        self.birth_day_entry = DateEntry(self.info_frame,date_pattern="yyyy-mm-dd",width=46)
        self.email_entry = Entry(self.info_frame,width=50)
        self.phone_entry = Entry(self.info_frame,width=50)
         # create combobox
        options = ["Female", "Male"]
        self.sex_combobox = ttk.Combobox(self.info_frame, values=options, state='readonly',width=46,foreground="#85929e")
        self.adress_entry = Entry(self.info_frame,width=50)
        
        self.header_label.config(text="Add new patient")
       
       

        self.first_name_label.grid(row=rowf, column=0)
        self.first_name_entry.grid(row=rowf, column=1)

        self.last_name_label.grid(row=rowf+1, column=0)
        self.last_name_entry.grid(row=rowf+1, column=1)

       
        self.birth_day_label.grid(row=rowf+2 , column=0)
        self.birth_day_entry.grid(row=rowf + 2, column=1)

        self.sex_label.grid(row=rowf+3, column=0)
        self.sex_combobox.grid(row=rowf+3, column=1)

        self.phone_label.grid(row=rowf, column=2)
        self.phone_entry.grid(row=rowf, column=3)
        rowf=1

        
        
        self.email_label.grid(row=rowf, column=2)
        self.email_entry.grid(row=rowf , column=3)

        self.adress_label.grid(row=rowf + 1, column=2)      
        self.adress_entry.grid(row=rowf + 1, column=3)



        #self.symptom_label.grid(row=rowf + 5, column=0)
        #self.symptom_text.grid(row=rowf + 6, column=1)
 
        self.submit_button.config( text="Save")
        #self.info_frame.columnconfigure(1, weight=1)
        for widget in self.info_frame.winfo_children():
            widget.grid_configure(padx=15,pady=10,sticky='w')

    def create_diag_frame(self):

        mydb=connection_to_db()
        mycursor=mydb.cursor()
        mycursor.execute("select distinct id from patient where doctor_id=%s",(self.doctor_id,))
        res=mycursor.fetchall()
        patientID=[]
        for row in res:
            for itm in row:
                patientID.append(itm)
                
        self.header_label.config(text="Prescribe a treatment")

        diagFramefils=Frame(self.diag_frame)
        diagFramefils.pack(padx=50,pady=20)
        self.patientId = Label(diagFramefils, text="Patient id : ")
        self.diagnostic = Label(diagFramefils, text="Diagnostic : ")
        self.diagnostic_note = Label(diagFramefils, text="Diagnostic Note :")
        self.diag_note_entry=Text(diagFramefils,height=2,width=55)
        #self.treatment = Label(self.diag_frame, text="Treatment : ")
        #ttk.Combobox(self.medication_frame, width=30, values=medications)
        self.patientId_entry = ttk.Combobox(diagFramefils,width=70,values=patientID)
        self.diagnostic_entry = Entry(diagFramefils,width=73)
        #self.treatment_text = Text(self.diag_frame, width=30, height=6)

        self.patientId.grid(row=0,column=0,sticky="w")
        self.patientId_entry.grid(row=0,column=1,sticky="w")

        self.diagnostic.grid(row=1,column=0,sticky="w")
        self.diagnostic_entry.grid(row=1,column=1,sticky="w")

        self.diagnostic_note.grid(row=2,column=0,sticky="w")
        self.diag_note_entry.grid(row=2,column=1,sticky="w")

        #self.treatment.grid(row=2,column=0)
        #self.treatment_text.grid(row=3,column=1)
        for widget in self.diag_frame.winfo_children():
            for itm in widget.winfo_children():
                itm.grid_configure(padx=20,pady=10)
        
    
    def create_treatment_frame(self):
        treatment_label_frame=Frame(self.treatmnet_frame)
        treatment_note_frame=Frame(self.treatmnet_frame)
        self.treatment_label = Label(treatment_label_frame, text="Treatment Name* : ")
        self.treatment_entry = Entry(treatment_label_frame,width=70)
        self.treatment_note_label = Label(treatment_note_frame, text="Treatment Note :")
        self.treatment_note_entry=Text(treatment_note_frame,height=2,width=60)
       
        self.liststocktraitement=[]
        Button(self.treatmnet_frame, text="➕",command= lambda: self.add_Item(self.liststocktraitement)).grid(row=0,column=1,sticky="w")

        #delete_button = Button(self.treatmnet_frame, text='❌', command=lambda: self.deleteItem(item_frame,text))

        qst_frame=Frame(self.treatmnet_frame)
        
        self.medication_qst = Label(qst_frame, text="Does this treatment require medication?")
        
        # Variable to store selected option
        self.selected_option = StringVar(value="no")

        # Function to display the selected option
        
        self.canvas = Canvas(self.treatmnet_frame, bg="#dfe7fd")
        self.frame = Frame(self.canvas, bg="#dfe7fd")
        self.scrollbar = Scrollbar(self.treatmnet_frame, orient="vertical", command=self.canvas.yview)

        self.canvas.configure(yscrollcommand=self.scrollbar.set, height=150, width=650)
        self.canvas.grid(row=3, column=0)  # Aligner le canvas à gauche
        self.scrollbar.grid(row=3, column=1, sticky="ns")  # Ajuster la scrollbar à droite

        # Créer la fenêtre à l'intérieur du canvas et l'aligner à gauche
        self.canvas.create_window((0, 0), window=self.frame, width=650)

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

        self.treatment_label.grid(row=0,column=0)
        self.treatment_entry.grid(row=0,column=1)

        self.treatment_note_label.grid(row=0,column=0)
        self.treatment_note_entry.grid(row=0,column=1)

        self.medication_qst.grid(row=0,column=0,sticky="w")
        radio1.grid(row=0,column=1,sticky="w",padx=10)
        radio2.grid(row=0,column=2,sticky="w",padx=10)
        for widget in self.treatmnet_frame.winfo_children():
                widget.grid_configure(padx=10,pady=5,sticky="w")



    def on_selection(self):
        if  self.treatment_entry.get():
        # Check if the treatment entry is filled
            print( self.selected_option.get())
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

                    self.medi_name_entry = ttk.Combobox(self.medication_frame, width=30, values=medications)
                    self.medi_dosage_entry = Entry(self.medication_frame, width=30)
                    self.medi_duree_entry = Entry(self.medication_frame)
                
                    self.medi_name_label.grid(row=0, column=0)
                    self.medi_name_entry.grid(row=1, column=0)
                    self.medi_dosage_label.grid(row=0, column=1)
                    self.medi_dosage_entry.grid(row=1, column=1)

                    self.medi_duree_label.grid(row=0, column=2)
                    self.medi_duree_entry.grid(row=1, column=2)
                    for widget in self.medication_frame.winfo_children():
                        widget.grid_configure(padx=10,pady=5)
                    
                else:
                    # If medication_frame exists but is hidden, show it
                    self.medication_frame.grid(row=2, column=0)
            else:
                print('k')
                # Handle case for treatment without medication
                if hasattr(self, 'medication_frame'):
                    self.medication_frame.grid_forget()  # Hide medication frame

                
                if not hasattr(self, 'treatment_duree_frame'):
                    print('l')
                    self.treatment_duree_frame=Frame(self.treatmnet_frame)
                    self.medi_duree_label = Label(self.treatment_duree_frame, text="Treatment duration* : ")
                    self.medi_duree_entry = Entry(self.treatment_duree_frame,width=60)
                    self.treatment_duree_frame.grid(row=2,column=0,sticky="w")
                    self.medi_duree_label.grid(row=0,column=0,sticky="w")
                    self.medi_duree_entry.grid(row=0,column=1,sticky="w")
                else:
                    self.treatment_duree_frame.grid(row=2, column=0,sticky="w")


        
    def create_buttons(self):
        self.button_frame = Frame(self.main_frame,bg="white")
        self.button_frame.grid(row=1, column=0,pady=4)

        self.add_patient_button = Button(self.button_frame, text="Add new patient",
                                         command=self.addPatient,
                                         bg="blue",fg='white', font=("Arial", 10, "bold"),
                                           bd=3,width=30)
        self.prescribe_treatment_button = Button(self.button_frame, text="Prescribe a treatment",
                                                   command=self.prescribe,
                                                   bg="green",fg='white' ,font=("Arial", 10, "bold"),
                                                    bd=3,width=30)
        
        self.add_patient_button.grid(row=0, column=0)
        self.prescribe_treatment_button.grid(row=0, column=2)
        for widget in self.button_frame.winfo_children():
            widget.grid_configure(padx=80,pady=10)
        

    def prescribe(self):
        print(self.submit_button.cget('text'))
        self.create_diag_frame()
        self.create_treatment_frame()
        self.info_frame.grid_forget()
        self.basicMedicalInf_frame.grid_forget()
        self.Symptom_frame.grid_forget()
        self.submit_button.config(text="Submit")
        print(self.submit_button.cget('text'))
        self.diag_frame.grid(row=2, column=0,pady=5,padx=90)
        self.treatmnet_frame.grid(row=3, column=0,padx=90)

    def addPatient(self):
        self.create_info_frame()
        self.create_basicMedicalInf_frame()
        self.create_symptome_frame()
        self.basicMedicalInf_frame.grid(row=4, column=0,pady=10,padx=20)
        self.info_frame.grid(row=3, column=0,pady=8,padx=20)
        self.Symptom_frame.grid(row=5, column=0)
        self.diag_frame.grid_forget()
        for widget in self.diag_frame.winfo_children():
            widget.forget()
        self.treatmnet_frame.grid_forget()

       
       

        

######################################

# Récupération de l'argument passé (ex: depuis PHP)
if len(sys.argv) > 1:
    variable = sys.argv[1]
    print("Argument reçu :", variable)
    print("Nom du script :", sys.argv[0])
else:
    variable = ""
    print("Aucun argument reçu.")

# Connexion à la base de données
connection_to_db()

# Création de l'interface Tkinter
window = Tk()
app = create_widget(window, variable)
window.mainloop()

    
