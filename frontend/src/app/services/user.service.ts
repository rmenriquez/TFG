/**
 * Created by RaquelMarcos on 18/6/19.
 */
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import { GLOBAL } from './global';
import { User } from '../models/user';

@Injectable({
    providedIn: 'root'
})
export class UserService {
    public url: string;
    public identity: User = new User(1,'name','user',0,0,0,0,'email','password');
    public token: string;
    //private currentUserSource = new BehaviorSubject<User>(null);
    //public currentUser = this.currentUserSource.asObservable();

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
        let aux = user;
        this.identity.user = user.user;
        this.identity.password = user.password;
        console.log(this.identity);
        console.log(aux);
        console.log(aux.name);
        console.log(user.n_cli_others);

        this.token = 'Basic ' + btoa(user.user + ':'+user.password);
        console.log(this.token);
        localStorage.setItem('token', this.token);
        //this.identity = this.user;
        //localStorage.setItem('identity', JSON.stringify(this.identity));

        //console.log(this.identity);
        localStorage.setItem('identity del signUp', JSON.stringify(this.identity));

        let headers = new HttpHeaders({'Content-Type': 'application/json',
            'Authorization': 'Basic ' + btoa(user.user + ':'+user.password)});

        return this._http.get(`http://localhost:8888/TFG/backend/rest/user/${user.user}`,
            {headers: headers});
    }

    getIdentity(){
        let identity = JSON.parse(localStorage.getItem('identity'));
        console.log('identity en getIdentity ');
        console.log(identity);
        if(identity != undefined){
            this.identity = identity as User;
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
        console.log(this.token);
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