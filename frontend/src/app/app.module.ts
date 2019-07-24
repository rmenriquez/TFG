import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClient, HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { routing, appRoutingProviders} from './app.routing';
// import ngx-translate and the http loader
import {TranslateLoader, TranslateModule} from '@ngx-translate/core';
import {TranslateHttpLoader} from '@ngx-translate/http-loader';

//Componentes
import { AppComponent } from './app.component';

import { BasicAuthInterceptor } from './_helpers/basic-auth.interceptor';
//User
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';
import { UserDetailComponent } from './components/user-detail/user-detail.component';
//Default
import { DefaultComponent } from './components/default/default.component';
//Food
import { FoodNewComponent } from './components/food-new/food-new.component';
import { FoodEditComponent } from './components/food-edit/food-edit.component';
import { FoodDetailComponent } from './components/food-detail/food-detail.component';
import { FoodAllComponent } from './components/food-all/food-all.component';
//Event
import { EventNewComponent } from './components/event-new/event-new.component';
import { EventAllComponent } from './components/event-all/event-all.component';
import { EventEditComponent } from './components/event-edit/event-edit.component';
import { EventDetailComponent } from './components/event-detail/event-detail.component';
//Staff
import { StaffNewComponent } from './components/staff-new/staff-new.component';
import { StaffAllComponent } from './components/staff-all/staff-all.component';
import { StaffDetailComponent } from './components/staff-detail/staff-detail.component';
import { StaffEditComponent } from './components/staff-edit/staff-edit.component';
import { TranslationComponent } from './components/translation/translation.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    RegisterComponent,
    UserDetailComponent,
    DefaultComponent,
    FoodNewComponent,
    FoodEditComponent,
    FoodDetailComponent,
    FoodAllComponent,
    EventNewComponent,
    EventAllComponent,
    EventDetailComponent,
    EventEditComponent,
    StaffNewComponent,
    StaffAllComponent,
    StaffDetailComponent,
    StaffEditComponent,
    TranslationComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    routing,
    TranslateModule.forRoot({
      loader: {
        provide: TranslateLoader,
        useFactory: HttpLoaderFactory,
        deps: [HttpClient]
      }
    }),
  ],
  providers: [
      appRoutingProviders,
    { provide: HTTP_INTERCEPTORS, useClass: BasicAuthInterceptor, multi: true },

  ],
  bootstrap: [AppComponent]
})
export class AppModule { }

export function HttpLoaderFactory(http: HttpClient) {
  return new TranslateHttpLoader(http);
}