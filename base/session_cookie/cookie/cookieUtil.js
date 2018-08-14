/**
 * @class cookieApi实现对cookie的增删改查
 * @constructor
 * @return {Objct} 返回一个cookieApi对象
 * @example  var obj = new GcookieApi();
 * 
 */
function GcookieApi(kk){

}

/**
 * @method getCookie  获取指定name的cookie值
 * @param  {name} 需要获取的cookie的name值
 * @return {String} 如果该cookie存在就返回cookie值，不存在就返回空
 */
GcookieApi.prototype.getCookie=function(name){
	var cookieStr=document.cookie;
	if(cookieStr.length>0){
		var start =cookieStr.indexOf(name+"=");
		if(start>-1){
			start+=name.length+1;
			var end = cookieStr.indexOf(";",start);
			if(end===-1){
				end=cookieStr.length;
			}
		}
		return decodeURIComponent(cookieStr.slice(start,end));
	}
	return "";
}

/**
 * @method getAllCookies 返回跟js同源的所有的cookie
 * @return {String}  
 */
GcookieApi.prototype.getAllCookies=function(){
	return document.cookie;
}

/**
 * @method getCookiesByJson  以json的形式返回cookie。
 * @return {JSON} 将cookie已json的形式返回
 */
GcookieApi.prototype.getCookiesByJson=function(){
	//cookie中值不能直接为分号(;),document.cookie也不会返回有效期、域名和路径，所以可以使用分号(;)分隔cookie
	//使用JSON.parse的时候，字符串形式的对象。名和值必须使用双引号包裹，如果使用单引号就会报错  比如 JSON.parse("{'a':'1'}")是错误的  应该为JSON.parse('"a":"1"');
	var cookieArr =document.cookie.split(";");
	var jsonStr='{';
	for(var i=0;i<cookieArr.length;i++){
		var cookie=cookieArr[i].split("=");
		jsonStr+='"'+cookie[0].replace(/\s+/g,"")+'":"'+decodeURIComponent(cookie[1])+'",';
	}
	jsonStr=jsonStr.slice(0,-1);
	jsonStr+='}';
	return JSON.parse(jsonStr);

}

/**@method deleteCookie  如果要删除一个cookie，必须域名和path都跟已有的cookie相同
 * @param {String} name cookie名name
 * @param {String} value cookie值value
 * @param {Number} time  cookie的有效时间，比如2016-01-01 00:00:00，如果不设置expres为session。关闭浏览器后或者过了session时间就清除cookie,
 * @param {String} domain cookie的域名，默认为js文件所在域名
 * @param {String} path cookie路径，默认为当前路径js文件所在路径
 * @return {Undefined}
 */
GcookieApi.prototype.setCookie=function(name,value,time,domain,path){
	var str=name+"="+encodeURIComponent(value);
	if(time){
		var date = new Date(time).toGMTString();
		str+=";expires="+date;
	}
	str=domain?str+";domain="+domain : str;
	str=path?str+';path='+path :str;
	document.cookie=str;

}

/**
 * @description 要删除一个cookie，比如domain和path完全相同。如果设置cookie时没有设置domain和path，则删除时也不需要设置这两个值，如果设置了则删除时就需要传入这两个值
 * @param  {String} name   [cookie名]
 * @param  {String} domain [cookie domian值]
 * @param  {String} path   [cookie 路径]
 * @return {undefined}        [返回undefined]
 */
GcookieApi.prototype.deleteCookie=function(name,domain,path){
	var date = new Date("1970-01-01");
	var str=name+"=null;expires="+date.toGMTString();
	str=domain ? str+";domain="+domain : str;
	str=path ? str+";path="+path : str;
	document.cookie=str;
}

GcookieApi.prototype.deleteAllCookie=function(){
	var cookieJson=this.getCookiesByJson(),
	    str="",
	    date = new Date("1970-01-01");
	for(var i in cookieJson){
		str=i+"=null;expires="+date.toGMTString();
	}
	document.cookie=str;
}

