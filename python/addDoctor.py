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
import random
import string
def validate_email(email):
    pattern=r"^[\w\.-]+@[\w.-]+\.\w+$"
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

   


def focus_next_widget(event):
    event.widget.tk_focusNext().focus()
    return "break"  # empêche le comportement par défaut
def generate_password():
    mot=""
    for i in range(10):
        mot+=random.choice(string.ascii_uppercase + '0123456789') 
    return mot

def generate_username():
    mydb=connection_to_db()
    mycursor=mydb.cursor()
    mycursor.execute('select distinct username from doctor ')
    res=mycursor.fetchall()
    existingUsernames=[]
    for  row in res:
        for itm in row:
            existingUsernames.append(itm)

    #🛑🛑🛑🛑🛑🛑🛑
    # rows=[row for row in res]
    # existingUsernames=[itm for itm in rows[0] ]
    #🛑🛑🛑🛑🛑🛑🛑

    while True:
        nine_digit_number = random.randint(100_000_000, 999_999_999)
        car=random.choice(['A','S','H','R'])
        mot=car + str(nine_digit_number)
        if mot not in existingUsernames:
            return mot
        
def verify_cin(cin):
    mydb=connection_to_db()
    mycursor=mydb.cursor()
    mycursor.execute('select distinct CIN from doctor')
    res=mycursor.fetchall()
    cins=[]
    for row in res:
        for itm in row:
            cins.append(itm)
    return cin in cins

class create_widget():
    def __init__(self,master,doctor_id):
        self.doctor_id=doctor_id
        self.master = master
        master.geometry("1000x600")
        master.title("Doctor Registration")
        
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

        
        self.header_label = Label(self.main_frame,text="Add New Doctor", font=("Arial", 24, "bold"),bg='white') 
        self.header_label.grid(row=0,pady=20)
            
        self.info_frame = LabelFrame(self.main_frame,text="👨‍💼 Personnel Information : ",cursor="hand2",takefocus=True,font=("Arial", 16, "bold"),borderwidth=6, relief="groove", bg="white",fg="#44b0f8")
        self.professionalInf_frame = LabelFrame(self.main_frame,text="🏥Professional Information :", font=("Arial", 16, "bold"),borderwidth=6, relief="groove",fg="#44b0f8")
       
        btn_frame=Frame(self.main_frame,bg="white")
        btn_frame.grid(row=5, padx=20,sticky="w",pady=10)
        
        self.submit_button = Button(btn_frame,bg="blue", fg="white", text= "+ New Doctor",font=("Arial", 11, "bold"),
                                      relief=RAISED, bd=5,command=self.show_inf) 
        self.submit_button.grid(row=0, column=0)

        self.cancel_button = Button(btn_frame,bg="red",fg="white", text= "Cancel", font=("Arial", 11, "bold"),
                                      relief=RAISED, bd=5,command=self.cancel) 
        self.cancel_button.grid(row=0, column=1,padx=20)
        
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
        return self.first_name_entry.get() and self.role_entry.get() and self.last_name_entry.get() and self.cin_entry.get()   and self.first_name_entry.get()  and self.specialization_entry.get() 
    
    

    def show_inf(self):
            today = datetime.datetime.now()
            today = today.strftime("%Y-%m-%d")
            if not self.verify_info_add():
                messagebox.showerror("Error", "Information is missing. Please check the fields.")
            elif  not validate_email(self.email_entry.get()) and self.email_entry.get():
                messagebox.showerror("Erreur", "Email address is invalide.")
            elif verify_cin(self.cin_entry.get()):
                messagebox.showerror("Erreur", "This doctor is already exist.")

            else:
               
                values=[]
                keys=[]
                
                for widget in self.info_frame.winfo_children():
                    if isinstance(widget,Frame):
                        for itm in widget.winfo_children():
                            if isinstance(itm, (Entry, ttk.Combobox)):  # Check if the widget is Entry or Combobox
                                if itm.get() : # Get the value from Entry or Combobox
                                    values.append(itm.get()) 
                                else:
                                    values.append('None')
                            if isinstance(itm, Label):
                                keys.append(itm.cget("text")) 
                            
                for widget in self.professionalInf_frame.winfo_children():
                    if isinstance(widget, (Entry, ttk.Combobox)):  # Check if the widget is Entry or Combobox
                        if widget.get() : # Get the value from Entry or Combobox
                            values.append(widget.get())
                        else: values.append('None')
                    elif isinstance(widget, (Spinbox)): 
                        if widget.get() : 
                            values.append(widget.get())
                        else: values.append(0)
                    elif isinstance(widget, Frame):
                        for child in widget.winfo_children():
                            if isinstance(child, (Entry, ttk.Combobox)):  # Check if the widget is Entry or Combobox
                                if child.get() : # Get the value from Entry or Combobox
                                    values.append(child.get())
                                else: values.append('None')
                            elif isinstance(child, (Spinbox)):  # Check if the widget is Entry or Combobox
                                if child.get() : # Get the value from Entry or Combobox
                                    values.append(child.get())
                                else: values.append(0)
                            elif isinstance(child, Label):
                                keys.append(child.cget("text")) 
                    elif isinstance(widget, Label):
                        keys.append(widget.cget("text")) 
                self.doctor_info = dict(zip(keys, values))
                print(self.doctor_info)
                self.formulaire=Tk()
                self.formulaire.geometry("900x500")
                
                Label(self.formulaire,text=f"Registration Date : {today}",font=("Arial", 11, "bold"),foreground='white',background='#44b0f8',width=40).grid(row=0,column=0,sticky="ew",pady=10)
                
                self.formulaire.columnconfigure(0, weight=1)  # Permet l'expansion du label      
                Inf_erg_frame = LabelFrame(self.formulaire,relief="groove",borderwidth=4,text="Patient Personal and Health Information",font=("Arial", 16, "bold"),foreground='#44b0f8')
                Inf_erg_frame.grid(row=1,column=0,pady=10,padx=50)
 
                Label(Inf_erg_frame,text="Username : ",foreground="red").grid(row=1,column=0,sticky="w",padx=2)
                self.doctor_username=generate_username()
                Label(Inf_erg_frame,text=self.doctor_username,foreground="red").grid(row=1,column=1,sticky="w",padx=2)

                Label(Inf_erg_frame,text="Generated password : ",foreground="red").grid(row=2,column=0,sticky="w",padx=2)
                self.doctor_password=generate_password()
                Label(Inf_erg_frame,text=self.doctor_password,foreground="red").grid(row=2,column=1,sticky="w",padx=2)

                Label(Inf_erg_frame,text='').grid(row=0,column=2,padx=50)
                i=3
                j=0
                Label(Inf_erg_frame,text="Personnel Information :",font=("Arial", 11, "bold underline"),bg="#aeb6bf").grid(row=0,column=j,sticky="w",pady=10,padx=5)
                for value in self.doctor_info:
                    if value=='Degree :':
                        j=3
                        i=1
                        Label(Inf_erg_frame,text="Professional Information:",bg="#aeb6bf",font=("Arial", 11, "bold underline")).grid(row=0,column=j,sticky="w",pady=10)
                    Label(Inf_erg_frame,text=value).grid(row=i,column=j,sticky="w",padx=2,pady=5)
                    i+=1
                i=3
                j=1
                for value in self.doctor_info:
                    if value=='Degree :':
                        j=4
                        i=1
                    Label(Inf_erg_frame,text=self.doctor_info.get(value)).grid(row=i,column=j,sticky="w",padx=2,pady=5)
                    i+=1

                btn_frame=Frame(self.formulaire)
                btn_frame.grid(row=2,column=0,sticky='e')
                return_btn=Button(btn_frame,text="Return",
                                         command=self.return_fct,
                                          bg="red", fg="white", font=("Arial", 10, "bold"),bd=5)
                return_btn.grid(row=0,column=0,padx=10)
                Register_btn=Button(btn_frame,text="Save",
                                         command=self.register_info,
                                          bg="green", fg="white", font=("Arial", 10, "bold"),
                                          bd=5)
                Register_btn.grid(row=0,column=1,padx=10)
       



    def cancel(self):
        for frame in self.info_frame.winfo_children():
            if  isinstance(frame,Frame):
                for widget in frame.winfo_children():
                    if isinstance(widget,Entry):
                        widget.delete(0,END)
                    if isinstance(widget,Text):
                        widget.delete("1.0",END)
        for widget in self.professionalInf_frame.winfo_children():
            if  isinstance(widget,Frame):
                for itm in widget.winfo_children():
                    if isinstance(itm,(Entry,ttk.Combobox,Spinbox)):
                        itm.delete(0,END)
            if isinstance(widget,(Entry,ttk.Combobox,Spinbox)):
                widget.delete(0,END)

        

           
    
    def return_fct(self):
        self.formulaire.destroy()
    

    def register_info(self):
        values=list(self.doctor_info.values()) 
        fullname=values[1] + ' ' + values[2]
        i=0
        k=0
        for itm in values:
            if i!=0:
                values.remove(itm)
                break
            i+=1
        i=0 
        for itm in values:
            if i!=0:
                values.remove(itm)
                break
            i+=1
        values.insert(1,fullname)
        days=""
        for key,value in self.days.items():
            if value.get()==1:
                days+=f"{key}," 
        values.append(days)
        values.insert(0,self.doctor_username)
        
        values.insert(1,generate_password())
        print(len(values))
        print(generate_password())

        sql="""insert into doctor (
                                    username,
                                    password,
                                    role,
                                    full_name,
                                    gender,
                                    CIN,
                                    specialization,
                                    contact_info,
                                    email,
                                    Degree,
                                    Years_of_Experience,
                                    License_Issue_Date,
                                    License_Expiry_Date,
                                    Medical_License_Number,
                                    professional_memberships1,
                                    License_Issuing_Authority,
                                    professional_memberships2,
                                    working_hours,
                                    Official_Working_Hours,
                                    Weekly_Days_Off
                                )
                values (%s ,%s , %s , %s , %s , %s , %s , %s , %s , %s , %s , %s , %s , %s , %s , %s , %s , %s , %s , %s )
            """
        mydb = connection_to_db()
        mycursor=mydb.cursor()
        try:
            mycursor.execute(sql,values)
            mydb.commit()
        
        except Exception as e:
            print(e)      
        if mycursor.rowcount > 0:# Check if rows were affected
            messagebox.showinfo("Information", f"Dr.{fullname} has been added successfully!")
        else:
            print("No data was inserted.")

        # Close connections
        mycursor.close()
        mydb.close()

            
    

    
    def create_info_frame(self):
       
        color="#e3f2fd"
        # flat, groove, raised, ridge, solid, or sunken
       
        frstgrid=Frame(self.info_frame,bg=color)
        frstgrid.grid(row=0,column=0)
        
        Label(frstgrid, text="Role* : ",bg=color).grid(row=0,column=0)
        self.role_entry = ttk.Combobox(frstgrid,width=46,values=['Admin','Non admin'],state='readonly')
        self.role_entry.grid(row=0,column=1)
        rowf=1
        Label(frstgrid, text="First Name* : ",bg=color).grid(row=rowf,column=0)
        self.first_name_entry = Entry(frstgrid,width=50)
        self.first_name_entry.grid(row=rowf,column=1)

        Label(frstgrid, text="Last Name* : ",bg=color).grid(row=rowf+1,column=0)
        self.last_name_entry = Entry(frstgrid,width=50)
        self.last_name_entry.grid(row=rowf+1,column=1)
        Label(frstgrid, text="Gender : ",bg=color).grid(row=rowf+2,column=0)
        options = ["Female", "Male","Prefer not to say"]
        self.sex_combobox = ttk.Combobox(frstgrid, values=options, state='readonly',width=46,foreground="#85929e")
        self.sex_combobox.grid(row=rowf+2,column=1)
        Label(frstgrid, text="CIN * : ",bg=color).grid(row=0,column=2)
        self.cin_entry=Entry(frstgrid,  width=50)
        self.cin_entry.grid(row=0,column=3)
        
        Label(frstgrid, text="Specialization * : ",bg=color).grid(row=rowf,column=2)
        self.specialization_entry=Entry(frstgrid,  width=50)
        self.specialization_entry.grid(row=rowf,column=3)
        Label(frstgrid, text="Contact Info : ",bg=color).grid(row=rowf+1,column=2)
        self.phone_entry = Entry(frstgrid,width=50).grid(row=rowf+1,column=3)

        Label(frstgrid, text="Email address : ",bg=color).grid(row=rowf+2,column=2)
        self.email_entry = Entry(frstgrid,width=50)
        self.email_entry.grid(row=rowf+2,column=3)
       
        
 

        #self.info_frame.columnconfigure(1, weight=1)
        for widget in frstgrid.winfo_children():
            widget.grid_configure(padx=18,pady=15,sticky="w")


    def create_basicMedicalInf_frame(self):
       
        # flat, groove, raised, ridge, solid, or sunken
        
        self.height_frame= Frame(self.professionalInf_frame)
        self.bloodSmk_frame= Frame(self.professionalInf_frame)
        for widget in self.bloodSmk_frame.winfo_children():
            widget.grid_configure(padx=4)
        
        
        self.height_frame.grid(row=4,column=0,padx=5,pady=2)

        Label(self.height_frame, text="Degree :").grid(row=0,column=0,padx=5,pady=2)
        self.height_entry = ttk.Combobox(
                    self.height_frame,
                    values=["Consultant", "Specialist", "General Practitioner"],
                    state="readonly"
        )
        self.height_entry.grid(row=1,column=0,padx=5,pady=2)
       
        Label(self.height_frame, text="Years of Experience :").grid(row=0,column=1,padx=5,pady=2)
        self.weight_entry = Spinbox(self.height_frame, from_=0, to=100, width=28)
        self.weight_entry.grid(row=1,column=1,padx=5,pady=2)


        Label(self.bloodSmk_frame, text="License Issue Date : ").grid(row=0,column=1,padx=5,pady=2)
        self.smoking_combobox = DateEntry(self.bloodSmk_frame,width=30,date_pattern="yyyy-mm-dd")
        self.smoking_combobox.grid(row=1,column=1,padx=5,pady=2)

        Label(self.bloodSmk_frame, text="License Expiry Date : ").grid(row=0,column=0,padx=5,pady=2)
        self.blood_combobox = DateEntry(self.bloodSmk_frame,width=30,date_pattern="yyyy-mm-dd")
        self.blood_combobox.grid(row=1,column=0,padx=5,pady=2)


        Label(self.professionalInf_frame, text="Medical License Number : ").grid(row=2,column=1,padx=5,pady=2)
        self.bmi_entry = Entry(self.professionalInf_frame,width=50)
        self.bmi_entry.grid(row=3,column=1,padx=5,pady=2)


        Label(self.professionalInf_frame, text="Professional Memberships 1  : ").grid(row=0,column=0)
        self.physical_condition_entry = ttk.Combobox(self.professionalInf_frame,
                                                     width=50,
                                                     foreground="#85929e",
                                                     values=["None", "ADA", "RC", "BMA", "CMA"],
                                                     
                                                     )
        
        
        self.physical_condition_entry.set("None")
        self.physical_condition_entry.grid(row=1,column=0)


        Label(self.professionalInf_frame,text="License Issuing Authority  : ").grid(row=0,column=1)
        self.chronic_diseases_entry = Entry(self.professionalInf_frame,
                                                   width=50,
                                                  
                                                   foreground="#85929e")
        self.chronic_diseases_entry.grid(row=1,column=1)

        Label(self.professionalInf_frame, text="Professional Memberships 2  : ").grid(row=2,column=0)
        self.drug_entry = ttk.Combobox(self.professionalInf_frame,width=50,values=["None", "ADA", "RC", "BMA", "CMA"],foreground="white")
        
        
        self.drug_entry.set("None")
        self.drug_entry.grid(row=3,column=0)
        
        Label(self.professionalInf_frame, text="Working Hours* : ").grid(row=6,column=0)
        self.WorkingHours_entry=Spinbox(self.professionalInf_frame, from_=0, to=100, width=50)
        self.WorkingHours_entry.grid(row=7,column=0)


        Label(self.professionalInf_frame, text="Official Working Hours : ").grid(row=6,column=1)
        ttk.Combobox(
                    self.professionalInf_frame,
                    values=["8AM - 4PM", "9AM - 5PM", "10AM - 6PM"],
                    state="readonly",
                    width=46,
                ).grid(row=7,column=1)
        
       
        weekDays_frame=Frame(self.professionalInf_frame)
        Label(self.professionalInf_frame, text="Scheduled Days Off : ").grid(row=8, column=0)
        weekDays_frame.grid(row=8,column=1,sticky='w')
        self.days={
            'Monday':0,
            'Tuesday':0,
            'Wednesday':0,
            'Thursday':0,
            'Friday':0,
            'Saturday':0,
            'Sunday':0
        }
        i=0
        for day in self.days:
            self.days[day]=IntVar()
            Checkbutton(weekDays_frame, text=day, variable= self.days[day]).grid(row=0,column=i,padx=5,pady=10,sticky='w')
            i+=1
       

        
        

        

        

        
        self.bloodSmk_frame.grid(row=4,column=1,padx=5,pady=2)

        for widget in self.professionalInf_frame.winfo_children():
            widget.grid_configure(padx=0,pady=4)
            if isinstance(widget,(Entry,ttk.Combobox,Text)):
                widget.bind("<Return>", focus_next_widget)
            if isinstance(widget,Frame):
                   for itm in widget.winfo_children():
                       if isinstance(itm,(Entry,ttk.Combobox,Text)):
                            widget.bind("<Return>", focus_next_widget)

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
                # Handle case for treatment without medication
                if hasattr(self, 'medication_frame'):
                    self.medication_frame.grid_forget()  # Hide medication frame

                
                if not hasattr(self, 'treatment_duree_frame'):
                    self.treatment_duree_frame=Frame(self.treatmnet_frame)
                    self.medi_duree_label = Label(self.treatment_duree_frame, text="Treatment duration* : ")
                    self.medi_duree_entry = Entry(self.treatment_duree_frame,width=60)
                    self.treatment_duree_frame.grid(row=2,column=0,sticky="w")
                    self.medi_duree_label.grid(row=0,column=0,sticky="w")
                    self.medi_duree_entry.grid(row=0,column=1,sticky="w")
                else:
                    self.treatment_duree_frame.grid(row=2, column=0,sticky="w")


        
    

    def prescribe(self):
        self.create_diag_frame()
        self.create_treatment_frame()
        self.info_frame.grid_forget()
        self.professionalInf_frame.grid_forget()
        self.other_info_frame.grid_forget()
        self.submit_button.config(text="Submit")
        self.diag_frame.grid(row=2, column=0,pady=5)
        self.treatmnet_frame.grid(row=3, column=0)

    def addPatient(self):
        self.create_info_frame()
        self.create_basicMedicalInf_frame()
        self.professionalInf_frame.grid(row=4, column=0,pady=30,padx=30,sticky="w")
        self.info_frame.grid(row=3, column=0,pady=0,padx=30,sticky="w")
        #self.treatmnet_frame.grid_forget()

       
       

        

######################################

# Récupération de l'argument passé (ex: depuis PHP)
if len(sys.argv) > 1:
    variable = sys.argv[1]
else:
    variable = ""

# Connexion à la base de données
connection_to_db()

# Création de l'interface Tkinter
window = Tk()
app = create_widget(window, variable)
window.mainloop()

    
