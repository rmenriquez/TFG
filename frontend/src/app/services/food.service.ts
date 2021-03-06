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
    private food: Food;

    constructor(public _http: HttpClient) {
        this.url = GLOBAL.url;
    }

    createFood(food: Food): Observable<any>{
        let json = JSON.stringify(food);
        if( food['price'] == null || food.price == '' ){
            food['price'] = 0.0;
        }

        let headers = new HttpHeaders().set('Content-Type', 'application/json');

        return this._http.post(this.url + 'food', food, {headers: headers}).pipe(tap(response => {
            let identity = JSON.parse(localStorage.getItem('identity'));
            food.restaurant = identity['id_user'];
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

    setFoodAllergens(id_food, allergens): Observable<any>{


        let headers = new HttpHeaders().set('Content-Type', 'application/json');

        let json =  { allergens};
        return this._http.post(this.url + 'food/'+id_food+'/allergen', json, {headers:headers});
    }

    setFood(food){
        this.food = food;
    }

    updateFoodAllergens(id, toAdd, toDelete): Observable<any>{

        let headers = new HttpHeaders().set('Content-Type', 'application/json');

        let json =  { toAdd, toDelete};

        return this._http.put(this.url+ 'food/'+id+'/allergen', json,{headers: headers});
    }
}