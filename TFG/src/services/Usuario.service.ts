import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';

import { Observable, throwError } from 'rxjs';
import { map, catchError } from 'rxjs/operators';

import { Usuario } from '../models/Usuario';


@Injectable({
  providedIn: 'root'
})
export class UserService {

	baseUrl = 'http://localhost/TFG/api';
	users: Usuario[];

  constructor(private http: HttpClient) { }

  getAllUsers(): Observable<Usuario> {
  return this.http.get(this.baseUrl+'/list').pipe(map((res)=>{
    this.users = res['data'];
    return this.users;
  }),
    catchError(this.handleError));
  /*.pipe(
    map((res) => {
      this.users = res['data'];
      return this.users;
  }),
    catchError(this.handleError));*/
	}

	private handleError(error: HttpErrorResponse){
		console.log(error);

		//retorna un observable con un mensaje vistoso
		return throwError('Error! Algo ha ido mal.');
	}
}
