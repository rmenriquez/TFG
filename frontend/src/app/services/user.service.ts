/**
 * Created by RaquelMarcos on 18/6/19.
 */
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { tap } from 'rxjs/operators';
import { GLOBAL } from './global';
import { User } from '../models/user';

@Injectable({
    providedIn: 'root'
})
export class UserService {
    private url: string;

    private identity: User;// = new User(1,'name','user',0,0,0,0,'email','password', '');
    //public token: string;
    //private currentUserSource = new BehaviorSubject<User>(null);
    //public currentUser = this.currentUserSource.asObservable();

    constructor(
        private _http: HttpClient
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

    signUp(login: string, password: string): Observable<any>{
        console.log('credentials', login, password);
        
        let headers = new HttpHeaders({'Content-Type': 'application/json',
            'Authorization': 'Basic ' + btoa(login + ':'+password)});

        return this._http.get<User>(`http://localhost:8888/TFG/backend/rest/user/${login}`, {headers: headers})
        .pipe(
            tap(response => {
                this.identity = response;
                this.identity.password = password;
                //this.token = 'Basic ' + btoa(user.user + ':'+user.password);
                //console.log(this.token);
                ///localStorage.setItem('token', this.token);
                //this.identity = this.user;
                //localStorage.setItem('identity', JSON.stringify(this.identity));

                //console.log(this.identity);
                localStorage.setItem('identity', JSON.stringify(this.identity));

            })
        );
    }

    getIdentity() {
        let storedIdentity = localStorage.getItem('identity');

        let identity = JSON.parse(storedIdentity);
        //console.log('identity en getIdentity ');
        //console.log(identity);
        if(identity != undefined) {
            this.identity = identity as User;
        }else{
            this.identity = undefined;
        }
        return this.identity;
    }

    getToken() {
        let user = this.getIdentity();

        return user === undefined ? undefined : btoa(user.user + ':' + user.password);
        /*let token = localStorage.getItem('token');
        if(token != "undefined"){
            this.token = token;
        }else{
            this.token = null;
        }
        //console.log(this.token);
        return this.token;*/
    }

    /*public get currentUserValue(){
        return this.identity;
    }*/

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