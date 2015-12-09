/**
 * Created by tarun on 11/17/15.
 */

function pp(){
   if(document.getElementById('yescheck').checked){
       document.getElementById('pptuid').style.display = '';
   }else if(document.getElementById('nocheck').checked){
       document.getElementById('pptuid').style.display = 'none';
   }

}
function pm(){
    if(document.getElementById('yescheck1').checked){
        document.getElementById('pmtuid').style.display = '';
    }else if(document.getElementById('nocheck1').checked){
        document.getElementById('pmtuid').style.display = 'none';
    }

}
function res1(){
    if(document.getElementById('yescheck2').checked){
        document.getElementById('res1tuid').style.display = '';
    }else if(document.getElementById('nocheck2').checked){
        document.getElementById('res1tuid').style.display = 'none';

    }

}
function res2(){
    if(document.getElementById('yescheck3').checked){
        document.getElementById('res2tuid').style.display = '';
    }else if(document.getElementById('nocheck3').checked){
        document.getElementById('res2tuid').style.display = 'none';

    }

}

function resarea(){
    var a = document.getElementById('res_ar');
    var b = document.getElementById('reslabel');
    var c = document.getElementById('resareavalue');

    if(a.value === 'Other'){
        b.style.display='';
        c.style.display='';
    }else{
        b.style.display='none';
        c.style.display='none';
    }


}
function toCount(entrance,exit,text,characters) {
    var entranceObj=document.getElementById(entrance);
    var exitObj=document.getElementById(exit);
    var length=characters - entranceObj.value.length;
    if(length <= 0) {
        length=0;
        text='<span class="disable"> '+text+' <\/span>';
        entranceObj.value=entranceObj.value.substr(0,characters);
    }
    exitObj.innerHTML = text.replace("{CHAR}",length);
}
function cputimecompute(){
    var cptime = document.getElementById('cpu_time').value;
    var month = document.getElementById('months').value;
    var output = document.getElementById('info');
    var res = Math.floor(cptime/month);
    if(!isNaN(res))
    output.innerHTML = "The total cpu time for a month is rounded upto: " + res;
}