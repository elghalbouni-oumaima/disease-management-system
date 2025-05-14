from flask import Flask,request,jsonify
import subprocess

app = Flask(__name__)

def run_script(script_name,variable):
    
    try:
        subprocess.Popen(["python",script_name,variable])  # Runs AddPatient.py in a separate process
        return f"Tkinter window {script_name} opened!"
    except Exception as e:
        return jsonify({'error': str(e)}), 500


@app.route('/run-tkinter')
def run_tkinter():
    return run_script("python/AddPatient.py",request.args.get('var'))
    

@app.route('/run-addTratment')
def run_addTratment():
    return run_script("python/addTratment.py",request.args.get('var'))


@app.route('/run-addDiagnosis')
def run_addDiagnosis():
    return run_script("python/addDiagnosis.py",request.args.get('var'))
    
@app.route('/run-addDoctor')
def run_adddoctor():
    return run_script("python/addDoctor.py",request.args.get('var'))
    

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000)
