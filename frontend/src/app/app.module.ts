import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { routing, appRoutingProviders} from './app.routing';

//Componentes
import { AppComponent } from './app.component';

import { BasicAuthInterceptor } from './_helpers/basic-auth.interceptor';

import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';
import { DefaultComponent } from './components/default/default.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    RegisterComponent,
    DefaultComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    routing
  ],
  providers: [
      appRoutingProviders,
    { provide: HTTP_INTERCEPTORS, useClass: BasicAuthInterceptor, multi: true },

  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
