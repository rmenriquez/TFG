/**
 * Created by RaquelMarcos on 18/6/19.
 */
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { GLOBAL } from './global';
import { User } from '../models/user';

@Injectable()@Injectable({
    providedIn: 'root'
})
export class UserService {
    public url: string;
    public identity;
    public token: string;


    constructor(
        public _http: HttpClient
    ){
        this.url = GLOBAL.url;
    }

    pruebas(){
            return "Hola mundo!!";
    }

    register(user):Observable<any>{
        let json = JSON.stringify(user);
        let params = json;

        let headers = new HttpHeaders().set('Content-Type', 'application/json');

        return this._http.post(this.url+'user', params, {observe: "response",headers:headers});
    }

    signUp(user): Observable<any>{
        let json = JSON.stringify(user);
        let params = json;

        let headers = new HttpHeaders({'Content-Type': 'application/json', 'Authorization': 'Basic ' + btoa(user.user + ':'+user.password)});

        return this._http.get(`http://localhost:8888/TFG/backend/rest/user/${user.user}`,{headers: headers});
    }

    getIdentity(){
        let identity = JSON.parse(localStorage.getItem('identity'));

        if(identity != undefined){
            this.identity = identity;
        }else{
            this.identity = null;
        }
        return this.identity;
    }

    getToken(){
        let token = localStorage.getItem('token');

        if(token != "undefined"){
            this.token = token;
        }else{
            this.token = null;
        }
        return this.token;
    }

    public get currentUserValue(){
        return this.identity;
    }

    /*login(data){
        let headers = new HttpHeaders({'Content-Type': 'application/json', 'Authorization': 'Basic ' + btoa(data.user + ':'+data.password)});

        return this._http.get(`http://localhost:8888/TFG/backend/rest/user/${data.user}`,{headers: headers})
            .pipe(map(user => {
                //Almacena los datos de usuario y la credencial en el localStorage
                user.authdata = window.btoa(data.user + ':' + data.password);
                localStorage.setItem('currentUser', JSON.stringify(user));
                this.currentUserSubject.next(user);
                return user;
            }));
    }*/


}