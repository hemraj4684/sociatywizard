function ERR(z){Materialize.toast(z,5000,'red accent-3');}$(W).ready(function(){var editor=ace.edit("code");editor.setTheme("ace/theme/clouds");editor.getSession().setMode('ace/mode/html');W.getElementById("code").style.fontSize="14px";editor.setOptions({enableBasicAutocompletion:true,enableSnippets:true,enableLiveAutocompletion:false,minLines:15,wrap:true,maxLines:100});$('select[name="language"]').change(function(){var T=$(this),TV=T.val();switch(TV){case '1':case '20':case '60':case '57':case '59':case '58':case '55':case '50':case '21':case '22':case '26':case '27':case '28':case '32':editor.getSession().setMode('ace/mode/php');break;case '2':case '53':case '71':editor.getSession().setMode('ace/mode/sql');break;case '6':case '7':editor.getSession().setMode('ace/mode/csharp');break;case '67':case '36':editor.getSession().setMode('ace/mode/python');break;case '8':case '9':case '35':case '34':editor.getSession().setMode('ace/mode/ruby');break;case '62':case '54':editor.getSession().setMode('ace/mode/vbscript');break;case '13':editor.getSession().setMode('ace/mode/sqlserver');break;case '5':case '33':case '29':case '30':editor.getSession().setMode('ace/mode/java');break;case '10':case '19':case '38':case '39':case '40':case '41':case '42':case '43':case '44':case '45':case '73':case '69':editor.getSession().setMode('ace/mode/javascript');break;case '4':case '56':editor.getSession().setMode('ace/mode/css');break;default:editor.getSession().setMode('ace/mode/html');break;}});$('#new-tutorial').submit(function(e){EP(e);$('button[type=submit]').prop('disabled',true);loader_start();$('#part2-btn').html('Wait....');var T=$(this);$.ajax({type:'post',url:'/ajax/new-tutorial.php',dataType:'json',data:T.serialize()+'&code='+encodeURIComponent(editor.getSession().getValue()),success:function(r){loader_end();$('#part2-btn').html('Submit <i class="mdi-action-done right"></i>');$('button[type=submit]').prop('disabled',false);switch(r.error){case '1':show_one();ERR('Please Enter Title');$('#title').focus();break;case '3':show_one();ERR('Please Enter Permalink');$('#link').focus();break;case '6':show_two();ERR('Please select a language');break;case '7':show_two();$('#code').focus();ERR('Please write your code');break;case '10':show_one();ERR('Permalink can contain only alphanumeric, dashes, underscores and dots characters. No special characters at the end or begining. Minimum 2 characters.');$('#link').focus();break;case '19':show_one();ERR('Please Select A Primary Language');break;case '11':show_one();ERR('Permalink already exist. Please enter a new one.');$('#link').focus();break;case '17':show_one();ERR('Please Enter Tags');$('#tags').focus();break;case '18':show_one();ERR('Please Enter maximum 30 characters in tags');$('#tags').focus();break;case '12':show_one();ERR('Something went wrong. Please refresh the page and try again.');$('#link').focus();break;case '13':T[0].reset();editor.getSession().setValue('');Materialize.toast('Tutorial successfully created',5000,'indigo lighten-1');setTimeout(function(){location.href = '/user/edit-tutorial/'+r.success;},2000);break;case '14':show_two();ERR('Please Select A Valid Language.');break;case '15':show_one();ERR('Permalink cannot be a number');$('#link').focus();break;case '16':show_one();ERR('Conclusion is too long. Enter maximum 1000 characters.');break;case '2':show_one();ERR('Title is too long. Enter maximum 100 characters.');$('#title').focus();break;case '4':show_one();ERR('Permalink is too long. Enter maximum 100 characters');$('#link').focus();break;case '5':show_one();ERR('Description is too long. Enter maximum 1000 characters');$('#description').focus();break;case '8':show_two();ERR('Explanation is too long. Enter maximum 2000 characters');('#explain').focus();break;case '9':show_two();$('#heading').focus();$('.f-err').html('Heading is too long. Enter maximum 100 characters');break;case '49':location.href = '/login';break;case '50':location.reload();break;default:show_two();break;}}})})});