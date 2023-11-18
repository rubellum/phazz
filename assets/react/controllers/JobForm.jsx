import React from 'react';

import {ChakraProvider, Container, Heading, Link} from '@chakra-ui/react'

import validator from "@rjsf/validator-ajv8";
import Form from "@rjsf/chakra-ui";
import useSWR from 'swr'
import ErrorAlert from "../components/ErrorAlert";
import Loading from "../components/Loading";

export default function (props) {
    const fetcher = (...args) => fetch(...args).then(res => res.json())
    const params = {}
    if (props.id) {
        params.id = props.id
    }
    const query = new URLSearchParams(params)
    const {data, error, isLoading} = useSWR(`${props.path.render}?${query}`, fetcher)

    if (error) return <ErrorAlert/>
    if (isLoading) return <Loading/>

    const log = (type) => console.log.bind(console, type);

    const Submit = (type) => {
        let data = {
            ...type.formData,
            ...{id: props.id},
            ...{token: props.token},
        }
        fetch(
            props.path.action, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            })
            .then((response) => response.json())
            .then((responseJson) => {
                window.location.href = "/job/" + responseJson.result.id;
            })
    }

    return (
        <ChakraProvider>
            <Container my='1.5rem'>
                <Heading size='lg'><Link href='/'>Phazz</Link></Heading>
            </Container>
            <Container>
                <Heading mb='3' size='md'>{props.id ? 'Edit' : 'Create'} crawler</Heading>
                <Form
                    schema={data.formSchema}
                    uiSchema={data.uiSchema}
                    formData={data.formData}
                    validator={validator}
                    onSubmit={Submit}
                    onError={log('errors')}
                />
            </Container>
        </ChakraProvider>
    )
}
