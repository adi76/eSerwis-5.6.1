var NUM_CENTYEAR=30;var BUL_TIMECOMPONENT=false;var BUL_YEARSCROLL=true;var calendars=[];var RE_NUM=/^\-?\d+$/;function calendar1(_1){this.gen_date=cal_gen_date1;this.gen_time=cal_gen_time1;this.gen_tsmp=cal_gen_tsmp1;this.prs_date=cal_prs_date1;this.prs_time=cal_prs_time1;this.prs_tsmp=cal_prs_tsmp1;this.popup=cal_popup1;if(!_1){return cal_error("Error calling the calendar: no target control specified");}if(_1.value==null){return cal_error("Error calling the calendar: parameter specified is not valid target control");}this.target=_1;this.time_comp=BUL_TIMECOMPONENT;this.year_scroll=BUL_YEARSCROLL;this.id=calendars.length;calendars[this.id]=this;}function cal_popup1(_2){if(_2){this.dt_current=this.prs_tsmp(_2);}else{this.dt_current=this.prs_tsmp(this.target.value);this.dt_selected=this.dt_current;}if(!this.dt_current){return;}var _3=window.open("calendar.html?datetime="+this.dt_current.valueOf()+"&id="+this.id,"Calendar","width=200,height="+(this.time_comp?215:192)+",status=no,resizable=yes,top=200,left=200,dependent=yes,alwaysRaised=yes");_3.opener=window;_3.focus();}function cal_gen_tsmp1(_4){return (this.gen_date(_4)+" "+this.gen_time(_4));}function cal_gen_date1(_5){return (_5.getFullYear()+"-"+(_5.getMonth()<9?"0":"")+(_5.getMonth()+1)+"-"+(_5.getDate()<10?"0":"")+_5.getDate());}function cal_gen_time1(_6){return ((_6.getHours()<10?"0":"")+_6.getHours()+":"+(_6.getMinutes()<10?"0":"")+(_6.getMinutes())+":"+(_6.getSeconds()<10?"0":"")+(_6.getSeconds()));}function cal_prs_tsmp1(_7){if(!_7){return (new Date());}if(RE_NUM.exec(_7)){return new Date(_7);}var _8=_7.split(" ");return this.prs_time(_8[1],this.prs_date(_8[0]));}function cal_prs_date1(_9){var _a=_9.split("-");if(_a.length!=3){return cal_error("Niepoprawnie zapisany format daty: '"+_9+"'.\nAkceptowalny format : rrrr-mm-dd.");}if(!_a[2]){return cal_error("Niepoprawny format daty: '"+_9+"'.\nNie ma takiego dnia w tym miesiącu.");}if(!RE_NUM.exec(_a[2])){return cal_error("Niepoprawny numer dnia: '"+_a[0]+"'.\nDozwolone są tylko liczby.");}if(!_a[1]){return cal_error("Niepoprawny format daty: '"+_9+"'.\nNie ma takiego miesiąca.");}if(!RE_NUM.exec(_a[1])){return cal_error("Niepoprawny numer miesiąca: '"+_a[1]+"'.\nDozwolone są tylko liczby.");}if(!_a[0]){return cal_error("Niepoprawny format daty: '"+_9+"'.\nNie ma takiego roku.");}if(!RE_NUM.exec(_a[0])){return cal_error("Niepoprawny rok: '"+_a[2]+"'.\nDozwolone są tylko liczby.");}var _b=new Date();_b.setDate(1);if(_a[1]<1||_a[1]>12){return cal_error("Niepoprawny numer miesiąca: '"+_a[1]+"'.\nDozwolone są liczby z przedziału 01-12.");}_b.setMonth(_a[1]-1);if(_a[0]<100){_a[0]=Number(_a[0])+(_a[0]<NUM_CENTYEAR?2000:1900);}_b.setFullYear(_a[0]);var _c=new Date(_a[0],_a[1],0);_b.setDate(_a[2]);if(_b.getMonth()!=(_a[1]-1)){return cal_error("Niepoprawny numer dnia w wybranym miesiącu: '"+_a[1]+"'.\nDozwolone są liczby z przedziału 01-"+_c.getDate()+".");}return (_b);}function cal_prs_time1(_d,_e){if(!_e){return null;}var _f=String(_d?_d:"").split(":");if(!_f[0]){_e.setHours(0);}else{if(RE_NUM.exec(_f[0])){if(_f[0]<24){_e.setHours(_f[0]);}else{return cal_error("Invalid hours value: '"+_f[0]+"'.\nAllowed range is 00-23.");}}else{return cal_error("Invalid hours value: '"+_f[0]+"'.\nAllowed values are unsigned integers.");}}if(!_f[1]){_e.setMinutes(0);}else{if(RE_NUM.exec(_f[1])){if(_f[1]<60){_e.setMinutes(_f[1]);}else{return cal_error("Invalid minutes value: '"+_f[1]+"'.\nAllowed range is 00-59.");}}else{return cal_error("Invalid minutes value: '"+_f[1]+"'.\nAllowed values are unsigned integers.");}}if(!_f[2]){_e.setSeconds(0);}else{if(RE_NUM.exec(_f[2])){if(_f[2]<60){_e.setSeconds(_f[2]);}else{return cal_error("Invalid seconds value: '"+_f[2]+"'.\nAllowed range is 00-59.");}}else{return cal_error("Invalid seconds value: '"+_f[2]+"'.\nAllowed values are unsigned integers.");}}_e.setMilliseconds(0);return _e;}function cal_error(_10){alert(_10);return null;}