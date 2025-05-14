
const inputs = document.querySelectorAll(".input");

function addcl(){
	let parent = this.parentNode.parentNode;
	parent.classList.add("focus");
}

function remcl(){
	let parent = this.parentNode.parentNode;
	if(this.value == ""){
		parent.classList.remove("focus");
	}
}


inputs.forEach(input => {
	input.addEventListener("focus", addcl);
	input.addEventListener("blur", remcl);
});


var radio0=document.getElementById('radio0'),
radio1=document.getElementById('radio1'),
radio2=document.getElementById('radio2'),
radio3=document.getElementById('radio3'),
radio4=document.getElementById('radio4'),
radio5=document.getElementById('radio5'),
radio6=document.getElementById('radio6'),
input=document.getElementById('Weekly_Days_Off');
function ischecked(obj){
	if(obj.checked){
		input.value = obj.value + ',';
	}
}
function getweeksday(){
	ischecked(radio0);
	ischecked(radio1);
	ischecked(radio3);
	ischecked(radio4);
	ischecked(radio5);
	ischecked(radio6);
}






