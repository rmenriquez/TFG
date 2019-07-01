/**
 * Created by RaquelMarcos on 26/6/19.
 */
import { Injectable } from '@angular/core';
import { HttpRequest, HttpHandler, HttpEvent, HttpInterceptor } from '@angular/common/http';
import { Observable } from 'rxjs';

import { UserService } from '../services/user.service';

@Injectable()
export class BasicAuthInterceptor implements HttpInterceptor {
    constructor(private _userService: UserService) { }

    intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        // add authorization header with basic auth credentials if available
        //console.log('_userService.CurrentUserValue en basic auth');
        //console.log(this._userService.currentUserValue);
       // const currentUser = this._userService.currentUserValue;
        //const currentUser = JSON.parse(localStorage.getItem('identity'));
        //console.log('currentUser en basic auth');
        //console.log(currentUser);
        //console.log(currentUser['authdata']);
        
        const token = this._userService.getToken();
        if (token !== undefined) {
            request = request.clone({
                setHeaders: {
                    Authorization: `Basic ${token}`
                }
            });
        }

        return next.handle(request);
    }
}