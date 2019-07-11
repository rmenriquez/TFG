/**
 * Created by RaquelMarcos on 11/7/19.
 */
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { GLOBAL } from './global';
import { Allergen } from '../models/allergen';

import { tap, retry } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class AllergenService {

  public _url: string;

  constructor(
      public _http: HttpClient
  ) {
    this._url = GLOBAL.url;
  }

  getAllergens(): Observable<any>{
    let headers = new HttpHeaders().set('Content-Type', 'application/json');

    return this._http.get(this._url+'allergen', {headers: headers}).pipe(retry(1));
  }
}
