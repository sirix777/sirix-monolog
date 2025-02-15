FROM reg-gitlab.btcbit.loc:3343/system/containers/php8.2-cli AS build

COPY . /var/www/

WORKDIR /var/www


FROM build AS testing

COPY --chmod=766 --from=build /var/www /otp/app

WORKDIR /otp/app

