/**
 * Created by RaquelMarcos on 18/6/19.
 */
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { tap } from 'rxjs/operators';
import { GLOBAL } from './global';
import { User } from '../models/user';
import { Md5 } from "md5-typescript";

@Injectable({
    providedIn: 'root'
})
export class UserService {
    private url: string;

    private identity: User;

    constructor(
        private _http: HttpClient
    ){
        this.url = GLOBAL.url;
    }

    register(user):Observable<any>{
        console.log(user);
        console.log(user['password']);
        let pwd = user['password'];
        user['password'] = Md5.init(pwd);
        let params = JSON.stringify(user as User);

        let headers = new HttpHeaders().set('Content-Type', 'application/json');

        return this._http.post(this.url+'user', params, {observe: "response",headers:headers});
    }

    signUp(login: string, password: string): Observable<any>{
        console.log('credentials', login, password);
        
        let headers = new HttpHeaders({'Content-Type': 'application/json',
            'Authorization': 'Basic ' + btoa(login + ':'+Md5.init(password))});

        return this._http.get<User>(`http://localhost:8080/TFG/backend/rest/user/${login}`, {headers: headers})
        .pipe(
            tap(response => {
                this.identity = response as User;
                this.identity.password = Md5.init(password);
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
    }

    viewUser(){
        let id_user = this.identity.id_user;
        console.log(id_user);
        let headers = new HttpHeaders().set('Content-Type', 'application/json');
        return this._http.get(this.url + 'user/' + id_user + '/view',{headers: headers});
    }


}