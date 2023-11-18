import React from 'react';

import {Box, Button, ChakraProvider, Container, Heading, Link, Table, Tbody, Td, Th, Tr} from '@chakra-ui/react'
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
    if (isLoading) return <Loading />

    const Submit = () => {
        let data = {
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
                window.location.href = "/task/" + responseJson.result.taskId;
            })
    }

    return (
        <ChakraProvider>
            <Container my='1.5rem'>
                <Heading size='lg'><Link href='/'>Phazz</Link></Heading>
            </Container>
            <Container>
                <Heading mb='3' size='md'>Run Crawl</Heading>

                <Heading mb='2' size='sm'>Crawl Information</Heading>

                <Table mb='2'>
                    <Tbody>
                        <Tr>
                            <Th>Name</Th>
                            <Td>{data.item.name}</Td>
                        </Tr>
                        <Tr>
                            <Th>URL</Th>
                            <Td>{data.item.url}</Td>
                        </Tr>
                        <Tr>
                            <Th>Type</Th>
                            <Td>{data.item.type}</Td>
                        </Tr>
                    </Tbody>
                </Table>
                <Heading mt='8' mb='4' textAlign='center' size='md'>Do you really want to execute this?</Heading>
                <Box textAlign='center'>
                    <Button onClick={Submit} colorScheme='red'>Execute</Button>
                </Box>
            </Container>
        </ChakraProvider>
    )
}
