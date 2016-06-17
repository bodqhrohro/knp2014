//initializing taskbar
var courses2014={};
courses2014.winno=0;
courses2014.maxz=0;
courses2014.winlist={};
courses2014.course_states=[
 'Предложен',
 'Идёт набор',
 'Набор окончен',
 'Курс завершён'
];
courses2014.lesson_types=[
 'Лекция',
 'Практика'
];
var dsMapper = function(v,i) {
 return {i: i, v: v};
};
var verbose_notifications=0;
//initialize basic UI
$(document).ready(function(){
 $('#mainmenu').kendoMenu();
 var notparams={
  draggable: false,
  position: {
   top: 37,
   right: 0
  }
 };
 $('#notifications').kendoPopup(notparam
s).hide();
 getNotifications();
 setInterval(getNotifications,20000);
});
//get the window node containing given
function curWindow(elem){
 var curElem=elem;
 while (curElem){
  curElem=curElem.parentNode;
  if (curElem.id){
   if (curElem.id.indexOf('mainwin')+1){
    return curElem;
   }
  }
 }
 return false;
}
//switch to entitie's window
function openTable(entity){
 //checking if already opened
 var window=getWindow(entity);
 if (window){
  window.toFront();
  return;
 }
 //locale
 kendo.culture("ru-UA");
 //describing basic container
 var elem=$('<div id=\'courses2014_windo
w_'+(++courses2014.winno)+'\'/>').append
To('#window_container');
 //CALLBACK: closing the window
 var closeWindow=function(e){
  courses2014.winlist[entity]=false;
 };
 var winparams={
  draggable: false,
  position: {
   top: 37,
   left: 1
  },
  actions: [
   "Seek-n",
   "Seek-s",
   "Maximize",
   "Close"
  ],
  close: closeWindow
 };
 //window labels (USE ASSOCS, MZFK!!!)
 switch (entity){
  case 'course':
   winparams['title']='Курсы';
  break;
  case 'ind_course':
   winparams['title']='Индивидуальные ку
рсы';
  break;
  case 'listener':
   winparams['title']='Слушатели';
  break;
  case 'teacher':
   winparams['title']='Преподаватели';
  break;
  case 'price':
   winparams['title']='Расценки';
  break;
  case 'settings':
   winparams['title']='Настройки';
  break;
  case 'user':
   winparams['title']='Пользователи';
  break;
 };
 //draw an empty window
 elem.kendoWindow(winparams);
 courses2014.winlist[entity]=courses2014
.winno;
 //CALLBACKS
 var bindWMButtonsEvents=function(){
  $('.k-i-seek-n').click(tileWindowUp);
  $('.k-i-seek-s').click(tileWindowDown)
;
  $('.k-i-maximize').click(false).click(
tileMaximize);
 };
 var tileWindowUp=function(e){
  var win=(($(e.currentTarget).closest('
.k-window')).find('[role=dialog]')).data
('kendoWindow');
  win.setOptions({
   height: (screen.availHeight-$('#mainm
enu').outerHeight())/2-($(e.currentTarge
t).closest('.k-window-titlebar')).outerH
eight()
  });
 };
 var tileWindowDown=function(e){
  var win=(($(e.currentTarget).closest('
.k-window')).find('[role=dialog]')).data
('kendoWindow');
  var height=(screen.availHeight-$('#mai
nmenu').outerHeight())/2-($(e.currentTar
get).closest('.k-window-titlebar')).oute
rHeight();
  win.setOptions({
   height: height,
   position: {
    top: 200
    //top: screen.availHeight-height
   }
  });
 };
 var tileMaximize=function(e){
  var win=(($(e.currentTarget).closest('
.k-window')).find('[role=dialog]')).data
('kendoWindow');
  win.setOptions({
   height: screen.availHeight-$('#mainme
nu').outerHeight(),
   position: {
    top: 0
   }
  });
  return false;
 };
 bindWMButtonsEvents();
 //1st is for View, 2nd is for Model
 var response1;
 //CALLBACK scheme -> datasource
 var showResult1=function(response){
  //special controls definition
  switch (entity){
   case 'course':
    //teachers dropdown
    response['columns'][2]['editor']=get
TeachersList;
    response['columns'][3]['editor']=get
TeachersList;
    response['columns'][6]['editor']=get
StateList;
    //listeners rollover
    response['detailInit']=getListenersB
yCourse;
   break;
   case 'ind_course':
    response['columns'][2]['editor']=get
TeachersList;
    response['columns'][3]['editor']=get
TeachersList;
    response['columns'][6]['editor']=get
StateList;
    response['columns'][8]['editor']=lis
tenerPicker;
   break;
   case 'listener':
    //courses rollover
    response['detailInit']=getCoursesByL
istener;
   break;
   case 'teacher':
    //degrees dropdown
    response['columns'][5]['editor']=get
DegreesList;
  }
  if (window.courses2014_editlock) {
   console.log(response['columns']);
  }
  //response['saveChanges']=function(e){
e.sender.refresh()};
  response=commonSchemeDef(response,1);
  response1=response;
  $.ajax(request2);
 };
 //CALLBACK datasource -> render
 var showResult2=function(response){
  var response2=response;
  response2=commonDataSourceDef(response
2,entity);
  response1['dataSource']=new kendo.data
.DataSource(response2);
  ($('<table/>').appendTo($(elem))).kend
oGrid(response1);
  //bindHotkeys(elem);
 };
 var request1={
  url: 'entities/'+entity+'/scheme.json'
,
  dataType: 'json',
  success: showResult1
 };
 var request2={
  url: 'entities/'+entity+'/dataSource.j
son',
  dataType: 'json',
  success: showResult2
 };
 $.ajax(request1);
}
//course selector
function AjaxPopupUserGroups(userid,elem
){
 $(elem).html('<img src=\'style/Default/
loading.gif\'>');
 var renderResponse=function(response){
  $(elem).html(response);
 };
 var request1={
  url: 'ajax/returnGroupsByUser.php',
  type: 'POST',
  data: {
   userid: userid
  },
  success: renderResponse
 };
 $.ajax(request1);
}
function getTeachersList(cont,opt){
 $('<input name="idTeacher" data-bind="v
alue:' + opt.field + '"/>')
  .appendTo(cont)
  .kendoDropDownList({
    autoBind: false,
    dataSource: {
     transport: {
      read: {
       url: "ajax/returnTeachers.php",
       dataType: "json"
      }
     }
    },
    dataTextField: 'TSNP',
    dataValueField: 'idTeacher',
    valuePrimitive: true
   });
}
/*function getListenersList(cont,opt){
 $('<input name="idListener" data-bind="
value:' + opt.field + '"/>')
  .appendTo(cont)
  .kendoDropDownList({
    autoBind: false,
    dataSource: {
     transport: {
      read: {
       url: "ajax/returnListeners.php",
       dataType: "json"
      }
     }
    },
    dataTextField: 'LSNP',
    dataValueField: 'idListener'
   });
}*/
function getDegreesList(cont,opt){
 $('<input name="degree" data-bind="valu
e:' + opt.field + '"/>')
  .appendTo(cont)
  .kendoDropDownList({
    autoBind: false,
    dataSource: {
     transport: {
      read: {
       url: "ajax/returnDegrees.php",
       dataType: "json"
      }
     }
    },
    dataTextField: 'deglab',
    dataValueField: 'degree'
   });
}
function getStateList(cont,opt){
 $('<input name="state" data-bind="value
:' + opt.field + '"/>')
  .appendTo(cont)
  .kendoDropDownList({
    autoBind: false,
    dataSource: {
     data: courses2014.course_states.map
(dsMapper)
    },
    dataTextField: 'v',
    dataValueField: 'i'
   });
}
function getLessonTypeList(cont, opt) {
 $('<input name="state" data-bind="value
:' + opt.field + '"/>')
  .appendTo(cont)
  .kendoDropDownList({
    autoBind: false,
    dataSource: {
     data: courses2014.lesson_types.map(
dsMapper)
    },
    dataTextField: 'v',
    dataValueField: 'i'
   });
}
function listenerPicker(cont,opt){
 var $container=$('<div/>').css('z-index
',100000).attr('id','listenerPicker').ke
ndoPopup({
  draggable: false,
  anchor: cont,
  collision: 'fit',
  origin: 'top left'
 });
 //CALLBACK
 var initTabChooser=function(response){
  $container.html(response);
  var popup = $container.data('kendoPopu
p');
  var initTreeView=function(data) {
   var ds={};
   ds['data']=data;
   ds['scheme']={
    model: {
     id: "id",
     hasChildren: "num",
     data: "items"
    }
   };
   var tree = $container.find('#treeItem
List').kendoTreeView({
    dataSource: new kendo.data.Hierarchi
calDataSource(ds),
    select: function(e) {
     $input.val(tree.data('kendoTreeView
').dataItem(e.node).id);
     $input.trigger('change');
     popup.destroy();
     $container.remove();
    }
   });
  };
  var removeTabChooser=function() { $('#
listenerPicker').remove(); };
  $.getJSON("ajax/returnListeners.php",i
nitTreeView);
  $('#modalButtons').hide();
  popup.open();
 };
 var request={
  url: 'lib/tabChooserTemplate.html',
  success: initTabChooser
 };
 $.ajax(request);
 var $input = $('<input type="hidden" na
me="idListener" data-bind="value:' + opt
.field + '"/>')
  .appendTo(cont);
}
function lessonsEditor(idCourse, name){
 var entity='lesson';
 var $container=$('<div/>')
  .css({'z-index':100001, 'max-width': '
600px'})
  .attr('id','lessonsEditor')
  .appendTo('body')
  .kendoWindow({
   modal: true,
   title: 'Расписание курса «' + name + 
'»'
  });
 var response1;
 var showResult1=function(response){
  response['columns'][2]['editor']=getLe
ssonTypeList;
  response1=response;
  $.ajax({
   url: 'entities/'+entity+'/dataSource.
json',
   dataType: 'json',
   success: showResult2
  });
 };
 var showResult2=function(response){
  var response2=response;
  response1=commonSchemeDef(response1,en
tity);
  response2=commonDataSourceDef(response
2,entity,idCourse);
  response2.schema.model.fields.idCourse
.defaultValue=idCourse;
  response1['dataSource']=new kendo.data
.DataSource(response2);
  $('<div/>').appendTo($container).css({
   width:'auto',
   float:'left'
  }).kendoGrid(response1);
  $container.data('kendoWindow').center(
);
 };
 $.ajax({
  url: 'entities/'+entity+'/scheme.json'
,
  dataType: 'json',
  success: showResult1
 });
}
function getCoursesByListener(e){
 var entity='courses_of_listener';
 var response1;
 var showResult1=function(response){
  response1=response;
 };
 var showResult2=function(response){
  var response2=response;
  response1=commonSchemeDef(response1,en
tity);
  response2=commonDataSourceDef(response
2,entity,e.data);
  response1['toolbar'][0]['template']='<
a class=\'k-button k-button-icontext\' o
nclick=\'selectCoursesExListeners('+e.da
ta.id+',this)\' href=\'javascript:void(0
)\'>Записать на курс</a>';
  response1['columns'][5]['command'][0][
'click']=setFullPaid;
  response1['dataSource']=new kendo.data
.DataSource(response2);
  $('<table/>').appendTo(e.detailCell).k
endoGrid(response1);
 };
 var setFullPaid=function(e){
  var spinner=$('<img src=\'style/Defaul
t/loading.gif\'>');
  $(e.target).prepend(spinner);
  var ids=this.dataItem($(e.target).clos
est('tr'));
  console.log(ids);
  var success=function(){
   spinner.hide();
  }
  $.ajax({
   url: 'entities/'+entity+'/update.php?
full=1&id='+ids.idCL,
   type: 'POST',
   data: {
    payment: ids.price
   },
   success: success
  });
 }
 var request1={
  url: 'entities/'+entity+'/scheme.json'
,
  dataType: 'json',
  success: showResult1
 };
 var request2={
  url: 'entities/'+entity+'/dataSource.j
son',
  dataType: 'json',
  success: showResult2
 };
 $.ajax(request1);
 $.ajax(request2);
}
function getListenersByCourse(e){
 var entity='listeners_of_course';
 var response1;
 var showResult1=function(response){
  response1=response;
  $.ajax(request2);
 };
 var showResult2=function(response){
  var response2=response;
  response1=commonSchemeDef(response1,2)
;
  response2=commonDataSourceDef(response
2,entity,e.data);
  response1['toolbar'][0]['template']='<
a class=\'k-button k-button-icontext\' o
nclick=\'selectListenersExCourses('+e.da
ta.id+',this)\' href=\'javascript:void(0
)\'>Записать слушателя</a>';
  response1['columns'][5]['command'][0][
'click']=setFullPaid;
  response1['dataSource']=new kendo.data
.DataSource(response2);
  $('<table/>').appendTo(e.detailCell).k
endoGrid(response1);
 };
 var setFullPaid=function(e){
  var spinner=$('<img src=\'style/Defaul
t/loading.gif\'>');
  $(e.target).prepend(spinner);
  var ids=this.dataItem($(e.target).clos
est('tr'));
  console.log(ids);
  var success=function(){
   spinner.hide();
  }
  $.ajax({
   url: 'entities/'+entity+'/update.php?
full=1&id='+ids.idCL,
   type: 'POST',
   data: {
    payment: ids.price
   },
   success: success
  });
 }
 var request1={
  url: 'entities/'+entity+'/scheme.json'
,
  dataType: 'json',
  success: showResult1
 };
 var request2={
  url: 'entities/'+entity+'/dataSource.j
son',
  dataType: 'json',
  success: showResult2
 };
 $.ajax(request1);
}
//CALLBACK
function pushParentTable(e){
 var grid=($($(e.sender)[0].list[0]).clo
sest('.k-grid'));//.find('[role=grid]');
//.data('kendoGrid');
 console.log(grid);
}
function callTabChooser(id,entity){
 var $container=$('<div/>').css('z-index
',100000).attr('id','tabChooserPopup').k
endoPopup({
  draggable: false,
  position: {
   top: 0,
   left: 0
  }
 });
 //CALLBACK
 var initTabChooser=function(response){
  $container.html(response);
  var initTreeView=function(data) {
   var ds={};
   ds['data']=data;
   ds['scheme']={
    model: {
     id: "id",
     hasChildren: "num",
     data: "items"
    }
   };
   ($container.find('#treeItemList')).ke
ndoTreeView({
    checkboxes: {
     checkChildren: true
    },
    loadOnDemand: false,
    dataSource: new kendo.data.Hierarchi
calDataSource(ds)
   });
  };
  var removeTabChooser=function() { $('#
tabChooserPopup').remove(); };
  var inList="";
  switch (entity) {
   case 'courses_of_listener':
    inList="ajax/returnCoursesExListener
s.php";
   break;
   case 'listeners_of_course':
    inList="ajax/returnListenersExCourse
s.php";
   break;
  }
  $.getJSON(inList+"?id="+id,initTreeVie
w);
  ($container.find('#modalOKButton')).cl
ick(function(e) {
   var i,j,items,checked=[];
   var til=$('#treeItemList');
   var ds=til.data('kendoTreeView').data
Source.view();
   for (i=0;i<ds.length;i++) {
    items=ds[i].items;
    for (j=0;j<items.length;j++) {
     if (items[j].checked)
      checked.push(items[j].id);
    }
   }
   var request={
    url: 'entities/'+entity+'/create.php
',
    type: 'POST',
    data: {
     ids: checked.join(':'),
     id: id
    },
    success: function(){
     refreshGrid(til);
     removeTabChooser();
    }
   };
   $.ajax(request);
  });
  ($container.find('#modalCancelButton')
).click(removeTabChooser);
 };
 var request={
  url: 'lib/tabChooserTemplate.html',
  success: initTabChooser
 };
 $.ajax(request);
 $container.show();
 return $container;
}
function selectCoursesExListeners(userid
,elem){
 var tcp=$('#tabChooserPopup');
 if (tcp.length) {
  tcp.remove();
 } else {
  $(elem).parent().append(callTabChooser
(userid,'courses_of_listener'));
 }
}
function selectListenersExCourses(course
id,elem){
 var tcp=$('#tabChooserPopup');
 if (tcp.length) {
  tcp.remove();
 } else {
  $(elem).parent().append(callTabChooser
(courseid,'listeners_of_course'));
 }
}
function subscribeListenerToCourse(elem,
lid,cid){
 $.ajax({
  url: 'entities/courses_of_listener/cre
ate.php?idListener='+lid+'&idCourse='+ci
d,
  success: function(){
   ($(elem).closest('.k-window')).remove
();
   $('.k-overlay').remove();
   var winno=courses2014.winlist[entity]
;
 if (winno){
  ($('#courses2014_window_'+winno).data(
'kendoWindow')).toFront();
  return;
 }
  }
 });
}
function recalcHeight(e){
 var rh=function(){
  var table=($(e.detailRow).closest('.k-
grid'))
  table.css({height:table.height()+$(e.d
etailRow).height()});
 };
 setTimeout(rh,2000);
}
function commonSchemeDef(response,type){
 //response['detailExpand']=recalcHeight
;
 /*var refresh=function(e){
  ((elem.find('table[data-role=grid]')).
data('kendoGrid')).refresh();
 };*/ //unneeded?
 response['pageable']={
  pageSize: 10,
  previousNext: false,
  messages: {
   display : "{0}-{1} из {2}"
  },
  refresh: true
 };
 response['resizable']=true;
 response['sortable']=true;
 response['scrollable']=true;//{'virtual
':'true'};
 response['navigatable']=true;
 if (!response['editable'])
  response['editable']={ confirmation: t
rue };
 response['edit']=tableEdit;
 response['save']=tableSave;
 //response['toolbar'].push({template: '
<a class=\'k-button k-button-icontext\' 
href=\'\\#\' onclick=\'refreshGrid(this)
;\'><span class=\'k-icon k-i-refresh\'><
/span>Обновить</a>'});
 return response;
}
function commonDataSourceDef(response,en
tity,data){
 if (response['schema']['model']['fields
']['affectedBy'])
  response['schema']['model']['fields'][
'affectedBy']['editable']=false;
 response['transport']={};
 response['batch']=false;
 response['serverPaging']=false;
 response['pageSize']=10;
 response['transport']['read']={url:'ent
ities/'+entity+'/read.php',dataType:'jso
n', cache: false};
 if (!window.courses2014_editlock) {
  if (entity!='user')
   response['transport']['create']={url:
'entities/'+entity+'/create.php',dataTyp
e:'json',type:'POST'};
  response['transport']['update']={url:'
entities/'+entity+'/update.php',dataType
:'json',type:'POST'};
  if (entity!='settings')
   response['transport']['destroy']={url
:'entities/'+entity+'/destroy.php',dataT
ype:'json',type:'POST'};
 }
 if (entity=='courses_of_listener'||enti
ty=='listeners_of_course') {
  response['transport']['read']['url']+=
'?id='+data.id;
  if (!window.courses2014_editlock) {
   response['transport']['create']['url'
]+='?id='+data.id;
   response['transport']['update']['url'
]+='?id='+data.id;
   response['transport']['destroy']['url
']+='?id='+data.idCL;
  }
 }
 if (entity=='lesson') {
  response['transport']['read']['url']+=
'?idCourse='+data;
 }
 return response;
}
function tableEdit(e){
 console.log(e.model);
 (e.container).addClass('edited_row');
}
function tableSave(e){
 $('edited_row').removeClass('edited_row
');
}
function getWindow(entity){
 var winno=courses2014.winlist[entity];
 return winno?($('#courses2014_window_'+
winno).data('kendoWindow')):false;
}
function refreshGrid(elem){
 var gd=(($(elem).closest('div.k-grid'))
.find('[data-role=grid]')).data('kendoGr
id');
 gd.dataSource.read();
 gd.refresh();
}
function bindHotkeys(elem){
 elem=$(elem);
 elem.bind('keypress','Ctrl+r',function(
){
  ($(elem.find('table[role=treegrid]')).
data('kendoGrid')).refresh();
  return false;
 });
 /*elem.data("kendoWindow").wrapper.find
(".k-i-refresh").attr({'accesskey':'r'})
;
 elem.data("kendoWindow").wrapper.find("
.k-grid-add").attr({'accesskey':'n'});
 elem.data("kendoWindow").wrapper.find("
.k-grid-save-changes").attr({'accesskey'
:'s'});
 elem.data("kendoWindow").wrapper.find("
.k-grid-cancel-changes").attr({'accesske
y':'c'});*/
}
function showNotifications(){
 $('#notifications').toggle();
}
function getNotifications(){
 //CALLBACK
 var buttonWrapper1=function(type,params
,confirm,text){
  return '<a class=\'k-button\' style=\'
float:right\' onclick=\'callConfirm("'+t
ype+'",'+JSON.stringify(params)+','+conf
irm+',this);\'>'+text+'</a>'
 };
 $('#notification_count').html('<img src
=\'style/Default/loading.gif\'>');
 var genNotificationList=function(respon
se){
  $('#notification_count').html(response
.length);
  var $container=$('#notifications');
  $container.html('');
  for (key in response) {
   $container.append($('<li class=\'noti
fication\'/>').html(
    verbose_notifications?
    response[key]['string']+
    (response[key]['warning'] ? '<br><sm
all>'+response[key]['warning']+'</small>
' : '')+
    buttonWrapper1(response[key]['type']
,response[key]['params'],1,'Принять')+
    (response[key]['params'] && response
[key]['params']['lid_old'] ? buttonWrapp
er1(response[key]['type'],response[key][
'params'],2,'Слить') : '')+
    buttonWrapper1(response[key]['type']
,response[key]['params'],0,'Отклонить')
    :response[key])
   );
  }
  $container.append(
   $('<a class=\'k-button\' onclick=\'ve
rbose_notifications=!verbose_notificatio
ns;getNotifications()\' />')
    .text(verbose_notifications?'Кратко'
:'Подробно')
  );
 }
 var request={
  url: 'ajax/getNotifications.php'+(verb
ose_notifications?'?verbose=1':''),
  dataType: 'json',
  success: genNotificationList
 };
 $.ajax(request);
}
function transferHandler(e) {
 var data = this.dataItem($(e.target).cl
osest("tr"));
 data.set('old', data.new);
}
function callConfirm(type,params,confirm
,elem,postData){
 if (confirm == 2) {
  var $container=$('<div/>')
   .css({'z-index':100001, 'max-width': 
'600px'})
   .attr('id','mergeDialog')
   .appendTo('body')
   .kendoWindow({
    modal: true,
    title: 'Слияние слушателей'
   });
  $.when(
   $.getJSON('entities/listener/read.php
?id='+params['lid_old']),
   $.getJSON('entities/listener/read.php
?id='+params['lid'])
  ).then(function(old, app) {
   var grid = $('<div/>').appendTo($cont
ainer).css({
    width:'auto',
    float:'left'
   }).kendoGrid({
    editable: true,
    toolbar: [ {name: 'save', 'text': 'С
охранить и удалить заявку'} ],
    saveChanges: function(e) {
     callConfirm(type, params, 1, elem, 
e.sender._data.reduce(function(prev, cur
) {
      prev[cur.title] = cur.old;
      return prev;
     }, {}));
    },
    dataBound: function() {
     this.dataSource.view().forEach(func
tion(entry) {
      if (entry.title == 'idListener' ||
 entry.title == 'affectedBy') {
       this.tbody.find('tr[data-uid="'+e
ntry.uid+'"]').hide();
      }
     }.bind(this));
    },
    columns: [
     { title: 'Поле', field: 'title', ed
itable: false },
     { title: 'Заявка', field: 'new', ed
itable: false },
     { command: [
	{ name: 'right', text: '', image
Class : 'k-icon no-text k-i-arrow-e', cl
ick: transferHandler }
       ],
       width: '56px'
     },
     { title: 'Слушатель', field: 'old' 
}
    ],
    dataSource: Object.keys(old[0]).map(
function(key) {
     return {
      'title': key,
      'old': old[0][key],
      'new': app[0][key]
     };
    })
   });
   $container.data('kendoWindow').center
();
   $('.no-text', $container).parent().cs
s('min-width', 0);
  });
  return;
 }
 var data={
  type: type,
  confirm: confirm
 };
 for (key in params)
  data[key]=params[key];
 var removeNotification=function(){
  $('#mergeDialog').data('kendoWindow').
close();
  $(elem).parent().slideUp();
 };
 var request={
  url: 'ajax/confirm.php?'+Object.keys(d
ata).map(function(key) { return key+'='+
data[key]; }).join('&'),
  type: postData?'POST':'GET',
  success: removeNotification,
  error: function(){errorBox(1);}
 };
 postData && (request.data = postData);
 $.ajax(request); 
}
function reportDialog(reportType){
 //CALLBACK
 var closeHandler=function(e){
  ($('#reportForm input[name=reportBegin
Date]').data('kendoDatePicker')).destroy
();
  ($('#reportForm input[name=reportEndDa
te]').data('kendoDatePicker')).destroy()
;
  (e.sender).destroy();
 }
 var $stub=$('<div/>');
 var winparams={
  position: {
   top: 37
  },
  actions: [
   'Close'
  ],
  title: 'Параметры отчёта',
  modal: true,
  close: closeHandler
 };
 $dialog=$stub.appendTo('body').kendoWin
dow(winparams);
 ($stub.data('kendoWindow')).center();
 //CALLBACK
 var initForm=function(response){
  $dialog.html(response);
  if (reportType) {
   //$('#reportForm select[name=reportTy
pe]').append('<option value=\''+reportTy
pe+'\'/>');
   $('#reportForm [name=reportType]').at
tr('value',reportType);
  }
  var year=(new Date().getFullYear());
  $('#reportForm input[name=reportBeginD
ate]').kendoDatePicker({
   value: (year-1)+'-09-01',
   format: '{0:yyyy-MM-dd}'
  });
  $('#reportForm input[name=reportEndDat
e]').kendoDatePicker({
   value: year+'-07-15',
   format: '{0:yyyy-MM-dd}'
  });
  //$('#reportForm select[name=reportTyp
e]').kendoDropDownList();
 }
 var request={
  url: 'lib/reportFormTemplate.html',
  success: initForm
 };
 $.ajax(request);
}
function errorBox(num){
 var s='';
 switch(num){
  case 1: s="Ошибка запроса"; break;
 }
 alert(s);
}
