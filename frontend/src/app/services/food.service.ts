/**
 * Created by RaquelMarcos on 27/6/19.
 */
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { GLOBAL } from './global';
import { Food } from '../models/food';
import { UserService } from './user.service';
import {User} from "../models/user";

@Injectable({
    providedIn: 'root'
})
export class FoodService {
    public url: string;
    //private currentUserSource = new BehaviorSubject<User>(null);
    //public currentUser = this.currentUserSource.asObservable();

    constructor(public _http: HttpClient) {
        this.url = GLOBAL.url;
    }

    pruebas() {
        return "Hola mundo!!";
    }

    createFood(food: Food, currentUser: User): Observable<any>{
        console.log(currentUser);
        let json = JSON.stringify(food);

        console.log(json);
        //let params = "json="+food;

        //.set('Authorization', 'Basic ' + currentUser.authdata)
        let headers = new HttpHeaders().set('Content-Type', 'application/json');

        return this._http.post(this.url + 'food', food, {headers: headers});
    }
}