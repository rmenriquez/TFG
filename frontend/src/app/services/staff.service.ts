/**
 * Created by RaquelMarcos on 05/7/19.
 */
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { GLOBAL } from './global';
import { Staff } from '../models/staff';

import { tap, retry } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class StaffService {
  private _url: string;

  constructor(public _http: HttpClient) {
    this._url = GLOBAL.url;
  }

  createStaff(staff: Staff): Observable<any>{
    let json = JSON.stringify(staff);

    if(staff.birthdate === ''){
      var date = new Date();
      staff.birthdate = date.getFullYear()-18 + '/' + (date.getMonth()+1) + '/' + date.getDay();
    }
    let headers = new HttpHeaders().set('Content-Type', 'application/json');

    return this._http.post(this._url + 'staff', staff, {headers: headers}).pipe(tap(response => {
      let identity = JSON.parse(localStorage.getItem('identity'));
      staff.restaurant = identity['id_user'];
    }));
  }

  getStaff(): Observable<any>{
    let headers = new HttpHeaders().set('Content-Type', 'application/json');

    return this._http.get(this._url + 'staff', {headers: headers}).pipe(retry(1));
  }

  viewStaff(id_staff): Observable<any>{
    let headers = new HttpHeaders().set('Content-Type', 'application/json');

    return this._http.get(this._url + 'staff/' + id_staff, {headers: headers});
  }

  updateStaff(staff, id_staff): Observable<any>{
    if(staff.birthdate === ''){
      var date = new Date();
      staff.birthdate = date.getFullYear()-18 + '/' + (date.getMonth()+1) + '/' + date.getDay();
    }
    let params = JSON.stringify(staff);

    let headers = new HttpHeaders().set('Content-Type', 'application/json');

    return this._http.put(this._url + 'staff/' + id_staff, params, { headers: headers});
  }

  deleteStaff(id_staff): Observable<any>{
    let headers = new HttpHeaders().set('Content-Type', 'application/json');

    return this._http.delete(this._url + 'staff/' + id_staff, {headers:headers});
  }


}
