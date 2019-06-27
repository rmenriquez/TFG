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
        console.log('CurrentUserValue en basic auth');
        console.log(this._userService.currentUserValue);
        const currentUser = this._userService.currentUserValue;
        console.log('currentUser en basic auth');
        console.log(currentUser);
        if (currentUser && currentUser.authdata) {
            request = request.clone({
                setHeaders: {
                    Authorization: `Basic ${currentUser.authdata}`
                }
            });
        }

        return next.handle(request);
    }
}