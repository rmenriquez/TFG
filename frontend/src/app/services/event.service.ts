/**
 * Created by RaquelMarcos on 05/7/19.
 */
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { GLOBAL } from './global';
import { Event } from '../models/event';

import { tap, retry } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class EventService {
  public _url: string;

  constructor(public _http: HttpClient) {
    this._url = GLOBAL.url;
  }

  createEvent(event: Event): Observable<any>{
    let json = JSON.stringify(event);
    console.log(json);
    console.log(event);

    let headers = new HttpHeaders().set('Content-Type', 'application/json');

    //return this._http.post(this._url + 'event', event, {headers: headers}).pipe(retry(1));
    return this._http.post(this._url + 'event', event, {headers: headers}).pipe(tap(response => {
      let identity = JSON.parse(localStorage.getItem('identity'));
      event.restaurant = identity['id_user'];
    }));
  }

  getEvents(): Observable<any>{
    let headers = new HttpHeaders().set('Content-Type', 'application/json');

    return this._http.get(this._url + 'event', {headers: headers}).pipe(retry(1));
  }

  viewEvent(id_event): Observable<any>{
    let headers = new HttpHeaders().set('Content-Type', 'application/json');

    return this._http.get(this._url + 'event/' + id_event, {headers: headers});
  }

  updateEvent(event, id): Observable<any>{
    let params = JSON.stringify(event);

    let headers = new HttpHeaders().set('Content-Type', 'application/json');

    return this._http.put(this._url + 'event/' + id, params, { headers: headers});
  }

  deleteEvent(id): Observable<any>{
    let headers = new HttpHeaders().set('Content-Type', 'application/json');

    return this._http.delete(this._url + 'event/' + id, {headers: headers});
  }
}
