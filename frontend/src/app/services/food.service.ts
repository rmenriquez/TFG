/**
 * Created by RaquelMarcos on 27/6/19.
 */
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { GLOBAL } from './global';
import { Food } from '../models/food';

import { tap, retry } from 'rxjs/operators';

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

    createFood(food: Food): Observable<any>{
        let json = JSON.stringify(food);
        console.log(json);
        console.log(food['price']);
        if( food['price'] == null || food.price == '' ){
            food['price'] = 0.0;
        }

        let headers = new HttpHeaders().set('Content-Type', 'application/json');

        return this._http.post(this.url + 'food', food, {headers: headers}).pipe(tap(response => {
            let identity = JSON.parse(localStorage.getItem('identity'));
            //console.log(identity);
            food.restaurant = identity['id_user'];
            //console.log(food);
        }));
    }

    getFoods(): Observable<any>{
        let headers = new HttpHeaders().set('Content-Type', 'application/json');

        return this._http.get(this.url + 'food', {headers: headers}).pipe(retry(1));
    }

    viewFood(id_food): Observable<any>{
        let headers = new HttpHeaders().set('Content-Type', 'application/json');
        return this._http.get(this.url + 'food/' + id_food,{headers: headers});
    }

    updateFood(food, id): Observable<any>{
        let params = JSON.stringify(food);

        let headers = new HttpHeaders().set('Content-Type', 'application/json');

        return this._http.put(this.url + 'food/' + id, params, { headers: headers});
    }

    deleteFood(id): Observable <any>{
        let headers = new HttpHeaders().set('Content-Type', 'application/json');

        return this._http.delete(this.url + 'food/' + id, {headers:headers});
    }
}