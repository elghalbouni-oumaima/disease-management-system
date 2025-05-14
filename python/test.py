import tkinter as tk

def focus_next_widget(event):
    event.widget.tk_focusNext().focus()
    return "break"  # empêche le comportement par défaut

root = tk.Tk()

entry1 = tk.Entry(root)
entry1.pack(pady=5)
entry1.bind("<Return>", focus_next_widget)

entry2 = tk.Entry(root)
entry2.pack(pady=5)
entry2.bind("<Return>", focus_next_widget)

entry3 = tk.Entry(root)
entry3.pack(pady=5)
entry3.bind("<Return>", focus_next_widget)

root.mainloop()
